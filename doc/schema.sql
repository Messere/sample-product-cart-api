create table if not exists product (
  id char(36) not null primary key,
  name varchar(255) not null,
  price_amount int not null,
  price_divisor int not null,
  price_currency char(3) not null
);

create table if not exists cartProduct (
  id char(36) not null primary key,
  name varchar(255) not null,
  price_amount int not null,
  price_divisor int not null,
  price_currency char(3) not null
);

create table if not exists cart(
  cart_id char(36) not null,
  cartProduct_id char(36) not null,
  amount int not null
);

insert or replace into product (id, name, price_amount, price_divisor, price_currency) values
('a9437a55-3169-4946-95e4-cde0e608e059', 'The Godfather', 5999, 100, 'PLN'),
('3cb23567-1691-419b-88e5-ba9e1d5e5950', 'Steve Jobs', 4995, 100, 'PLN'),
('6056d653-d3cf-4a94-8137-1b19d2d1728f', 'The Return of Sherlock Holmes', 3999, 100, 'PLN'),
('a9c8c870-0d34-47cd-a953-d89cf5424765', 'The Little Prince', 2999, 100, 'PLN'),
('cd5e8172-7838-46a7-b267-fd4df70787ec', 'I Hate Myselfie!', 1999, 100, 'PLN'),
('42c674b6-a79b-43a6-b1ef-f460f43588e4', 'The Trial', 999, 100, 'PLN');

insert or replace into cartProduct (id, name, price_amount, price_divisor, price_currency) values
('a9437a55-3169-4946-95e4-cde0e608e059', 'The Godfather', 5999, 100, 'PLN'),
('3cb23567-1691-419b-88e5-ba9e1d5e5950', 'Steve Jobs', 4995, 100, 'PLN'),
('6056d653-d3cf-4a94-8137-1b19d2d1728f', 'The Return of Sherlock Holmes', 3999, 100, 'PLN'),
('a9c8c870-0d34-47cd-a953-d89cf5424765', 'The Little Prince', 2999, 100, 'PLN'),
('cd5e8172-7838-46a7-b267-fd4df70787ec', 'I Hate Myselfie!', 1999, 100, 'PLN'),
('42c674b6-a79b-43a6-b1ef-f460f43588e4', 'The Trial', 999, 100, 'PLN');

