-- Drop Database Here
/*
drop database web;
drop table branches;
drop table customeraccounts;
drop table auctionproducts;
*/

-- Create database and use database
DROP DATABASE IF EXISTS web;
create database web;
use web;
-- Create and tables
-- Create branches 
create table branches (
branch_code int primary key auto_increment,
branch_name varchar(100) unique,
address varchar(100),
hotline_number char(10)
)engine=InnoDB;
/*
Create table customerAccounts
Note: In this table we hash the password at the front-end therefore we
cannot create sample for it.
*/

create table customerAccounts (
identificationNumber char(12) primary key,
email varchar(60) unique,
phone char(10) unique,
pass varchar(255),
fname varchar(20),
lname varchar(20),
address varchar(100),
city varchar(100),
country varchar(100),
picture varchar(255),
branch int not null,
balance double,
foreign key(branch) references branches(branch_code)
)engine=InnoDB;

/*
delete from customerAccounts where identificationNumber = '123456789012';
delete from customerAccounts where identificationNumber = '123456789013';
delete from customerAccounts where identificationNumber = '123456789014';
alter table customerAccounts add column picture varchar(255);
select * from customeraccounts;
*/
-- Create table auctionproducts
create table auctionproducts(
id int auto_increment primary key,
auction_name varchar (100),
minimum_price double,
closing_time datetime,
cid char(12) not null,
status tinyint not null default 0,
foreign key (cid) references customeraccounts(identificationNumber)
)engine=InnoDB;



-- Create table bidsHistory
create table bidsHistory(
customer_id char(12) not null,
product_id int not null,
bids double,
primary key (customer_id, product_id),
foreign key (customer_id) references customeraccounts(identificationNumber),
foreign key (product_id) references auctionproducts(id)
)engine=InnoDB;



-- Create table admin
create table admin (
username varchar (20) primary key,
pass varchar(255)
)engine=InnoDB;

-- Create table transactionhistory
create table transactionHistory (
id int auto_increment primary key,
seller char(12),
bidder char(12),
bid double,
time datetime,
status tinyint default 0,
foreign key (bidder) references customeraccounts (identificationNumber),
foreign key (seller) references customeraccounts (identificationNumber)
)engine=InnoDB;

-- Insert sample
insert into branches (branch_name, address, hotline_number)
values ("rmit1","701 Nguyen Van Linh, District 7, HCMC","0123456789"),
("rmit2","702 Nguyen Van Linh, District 1, HCMC","0123456789"),
("rmit3","703 Nguyen Van Linh, District 2, HCMC","0123456789"),
("rmit4","704 Nguyen Van Linh, District 3, HCMC","0123456789"),
("rmit5","705 Nguyen Van Linh, District 4, HCMC","0123456789"),
("rmit6","706 Nguyen Van Linh, District 5, HCMC","0123456789"),
("rmit7","707 Nguyen Van Linh, District 6, Hanoi","0123456789");
delimiter $$

-- Create index
CREATE INDEX index_time ON auctionproducts (closing_time); 
CREATE INDEX index_product_id_bidshistory ON bidshistory (product_id); 
CREATE INDEX index_customer_branch ON customeraccounts (branch); 
CREATE INDEX index_status ON auctionproducts (status); 
CREATE INDEX index_time_transaction ON transactionHistory (time); 

/*
Create function find_minprice
Note: this function return the min price that the user set at the begin
*/
delimiter $$
create function find_minprice(product_id INT)
returns double not deterministic READS SQL DATA
begin
	declare something double;
    select minimum_price INTO something
    from auctionproducts
    where id=product_id;
    return something;
end $$

delimiter ;

/*
Create function find_maxprice
Note: this function return current max bids of the auction products
*/
delimiter $$ 
create function find_maxprice(productid INT)
returns double not deterministic reads sql data
begin
	declare maxprice double;
    select max(bids) into maxprice
    from bidsHistory where product_id = productid;
    return maxprice;
end $$

delimiter ;



/*
Create trigger before set_bid
Note: this trigger check if the customer make the bid for the auction product
for the first time. If true check if there any other customer has a bid on 
this auction product. If true then insert a new bid for the customer and return
the money for the lower bidder.
*/
delimiter $$

create trigger set_bid
before insert on bidsHistory
for each row
begin 
	declare current_bid INT; 
    declare check_balance double;
    declare return_balance double;
    declare return_id char(12);
    set return_balance = 0;
    set current_bid = 0;
    set check_balance = 0;
    select count(*) into current_bid from bidsHistory where product_id = new.product_id;
    select balance into check_balance from customeraccounts where identificationNumber = new.customer_id;
    if current_bid = 0 then 
		if find_minprice(new.product_id) > new.bids then 
        signal sqlstate '45000' set message_text = 'cannot1';
        else 
			if check_balance < new.bids then 
            signal sqlstate '45000' set message_text = 'cannot2';
            else 
				update customeraccounts set balance = balance - new.bids where identificationNumber = new.customer_id;
            end if;
		end if;
	else 
		if find_maxprice(new.product_id) >= new.bids then
        signal sqlstate '45000' set message_text = 'cannot3';
        else 
			if check_balance < new.bids then
            signal sqlstate '45000' set message_text = 'cannot4';
            else 
            update customeraccounts set balance = balance - new.bids where identificationNumber = new.customer_id;
            select max(bids) into return_balance from bidshistory where product_id=new.product_id;
            select customer_id into return_id from bidshistory where product_id=new.product_id and bids = return_balance;
            update customeraccounts set balance = balance + return_balance where identificationNumber = return_id;  
            end if;
		end if;
	end if;
end $$
delimiter ;



/*
Create trigger before update_bid
Note: this trigger check if the customer make an update for the auction product
that they already have a bid for. If true check if there any other customer has a bid on 
this auction product. If true then update the current bid for the customer and return
the money for the lower bidder.
*/
delimiter $$

create trigger update_bid
before update on bidsHistory
for each row
begin 
	declare current_bid INT; 
    declare check_balance double;
        declare return_balance double;
    declare return_id char(12);
    set return_balance = 0;
    set current_bid = 0;
    set check_balance = 0;
    select count(*) into current_bid from bidsHistory where product_id = old.product_id;
    select balance into check_balance from customeraccounts where identificationNumber = old.customer_id;
    if current_bid = 0 then 
		if find_minprice(old.product_id) > new.bids then 
        signal sqlstate '45000' set message_text = 'cannot';
        else 
			if check_balance < new.bids then 
            signal sqlstate '45000' set message_text = 'cannot';
            else 
				update customeraccounts set balance = balance - new.bids where identificationNumber = new.customer_id;
            end if;
		end if;
	else 
		if find_maxprice(old.product_id) >= new.bids then
        signal sqlstate '45000' set message_text = 'cannot';
        else 
			if check_balance < new.bids then
            signal sqlstate '45000' set message_text = 'cannot';
            else 
            update customeraccounts set balance = balance - new.bids where identificationNumber = new.customer_id;
            select max(bids) into return_balance from bidshistory where product_id=new.product_id;
            select customer_id into return_id from bidshistory where product_id=new.product_id and bids = return_balance;
            update customeraccounts set balance = balance + return_balance where identificationNumber = return_id;  
            end if;
		end if;
	end if;
end $$
delimiter ;


/*
Create procedure check_set_bid
Note: This procedure check if there is any bid of the customer and auctions yet.
If true, then update.Else, insert a new record for the bidsHistory.
*/
delimiter $$
create procedure check_set_bid(in customerid char(12), productid int, new_bids double)
begin 
	declare checker int;
    set checker = 0;
select count(*) into checker
from bidshistory
where customer_id = customerid
and product_id = productid;
if checker = 0
then insert into bidshistory values (customerid, productid, new_bids);
else update bidshistory set bids = new_bids where customer_id = customerid and product_id = productid;
end if;
end $$
delimiter ; 


select auctionproducts.id, auctionproducts.minimum_price,auctionproducts.auction_name, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx  from auctionproducts
left join bidshistory on auctionproducts.id = bidshistory.product_id
group by (auctionproducts.id);

select auctionproducts.id as id, auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx,ifnull(count(*),0) as bidnum from auctionproducts
left join bidshistory on auctionproducts.id = bidshistory.product_id
group by (auctionproducts.id)
order by bidnum asc;

select a.auction_name as auction_name, a.minimum_price as minimum_price,bid.bids as total_payment, a.closing_time as closing_time from bidshistory bid
join auctionproducts a
on bid.product_id = a.id
where customer_id='123412341234';
select * from auctionproducts 
where cid='123412341234';

select * from customeraccounts;


select current_timestamp();

select * from admin;

select * from auctionproducts;


select id, max(bidshistory.bids) as bids, auctionproducts.cid as seller, bidshistory.customer_id as bidder from auctionproducts
join bidshistory
on auctionproducts.id = bidshistory.product_id
where closing_time<current_timestamp() and status =0
group by product_id;



/*
Select stament to display all the auction products that not finished yet
*/
select auctionproducts.id as id, ifnull(numbids.numbid,0) as numbid,auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
  left join bidshistory on auctionproducts.id = bidshistory.product_id left join (select product_id as prod_id,count(*) as numbid from bidsHistory group by bidsHistory.product_id) numbids on auctionproducts.id = numbids.prod_id 
  where CURRENT_TIMESTAMP() <= closing_time
  group by (auctionproducts.id);
  
/*
select all the bids 
*/
select id, max(bidshistory.bids) as bids, auctionproducts.cid as seller, bidshistory.customer_id as bidder from auctionproducts
join bidshistory
on auctionproducts.id = bidshistory.product_id
where closing_time<current_timestamp() and status =0
group by product_id;
-- select auctions order by asc maxbid
select auctionproducts.id as id, ifnull(numbids.numbid,0) as bidnum,auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
    left join bidshistory on auctionproducts.id = bidshistory.product_id 
    left join (select product_id as prod_id,count(*) as numbid from bidsHistory group by bidsHistory.product_id) numbids 
    on auctionproducts.id = numbids.prod_id 
    where CURRENT_TIMESTAMP() <= closing_time
    group by (auctionproducts.id)
    order by mx asc;
-- select auctions order by asc closing_time
    select auctionproducts.id as id, ifnull(numbids.numbid,0) as bidnum,auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
    left join bidshistory on auctionproducts.id = bidshistory.product_id left join (select product_id as prod_id,count(*) as numbid from bidsHistory group by bidsHistory.product_id) numbids on auctionproducts.id = numbids.prod_id 
    where CURRENT_TIMESTAMP() <= closing_time
    group by (auctionproducts.id)
    order by closing_time asc;
-- select auctions order by asc bid number
    select auctionproducts.id as id, ifnull(numbids.numbid,0) as bidnum,auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
    left join bidshistory on auctionproducts.id = bidshistory.product_id left join (select product_id as prod_id,count(*) as numbid from bidsHistory group by bidsHistory.product_id) numbids on auctionproducts.id = numbids.prod_id 
    where CURRENT_TIMESTAMP() <= closing_time
    group by (auctionproducts.id)
    order by bidnum asc;
-- select auctions order by desc maxbid
    select auctionproducts.id as id, ifnull(numbids.numbid,0) as bidnum,auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
    left join bidshistory on auctionproducts.id = bidshistory.product_id left join (select product_id as prod_id,count(*) as numbid from bidsHistory group by bidsHistory.product_id) numbids on auctionproducts.id = numbids.prod_id 
    where CURRENT_TIMESTAMP() <= closing_time
    group by (auctionproducts.id)
    order by mx desc;
-- select auctions order by desc closing time
    select auctionproducts.id as id, ifnull(numbids.numbid,0) as bidnum,auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
    left join bidshistory on auctionproducts.id = bidshistory.product_id left join (select product_id as prod_id,count(*) as numbid from bidsHistory group by bidsHistory.product_id) numbids on auctionproducts.id = numbids.prod_id 
    where CURRENT_TIMESTAMP() <= closing_time
    group by (auctionproducts.id)
    order by closing_time desc;
-- select auctions order by desc bid number
    select auctionproducts.id as id, ifnull(numbids.numbid,0) as bidnum,auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
    left join bidshistory on auctionproducts.id = bidshistory.product_id left join (select product_id as prod_id,count(*) as numbid from bidsHistory group by bidsHistory.product_id) numbids on auctionproducts.id = numbids.prod_id 
    where CURRENT_TIMESTAMP() <= closing_time
    group by (auctionproducts.id)
    order by bidnum desc;
-- select auctions normally
    select auctionproducts.id as id, ifnull(numbids.numbid,0) as bidnum,auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
  left join bidshistory on auctionproducts.id = bidshistory.product_id left join (select product_id as prod_id,count(*) as numbid from bidsHistory group by bidsHistory.product_id) numbids on auctionproducts.id = numbids.prod_id 
  where CURRENT_TIMESTAMP() <= closing_time
  group by (auctionproducts.id);
  
-- Create roles and grant access for each types of user
drop role if exists "customer","admin";
drop user if exists "customer"@"localhost", "admin"@"localhost";
create role "customer","admin";
grant all on web.customeraccounts to "customer";
grant select, insert, update on web.auctionproducts to "customer";
grant select, insert, update on web.bidshistory to "customer";
grant select,insert on web.transactionhistory to "customer";
grant select on web.branches to "customer";
GRANT EXECUTE ON PROCEDURE web.check_set_bid TO 'customer';
GRANT EXECUTE ON FUNCTION web.find_minprice TO 'customer';
GRANT EXECUTE ON FUNCTION web.find_maxprice TO 'customer';
grant all on web.* to "admin";
create user "customer"@"localhost" identified by "pass123";
create user "admin"@"localhost" identified by "pass123";
grant "customer" to "customer"@"localhost";
set default role "customer" to "customer"@"localhost";
grant "admin" to "admin"@"localhost";
set default role "admin" to "admin"@"localhost";