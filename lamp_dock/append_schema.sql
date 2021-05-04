CREATE TABLE history (
	order_id INT(11) AUTO_INCREMENT,
	purchased DATETIME NOT NULL,
	user_id INT(11) NOT NULL,
	primary key(order_id)
);

CREATE TABLE details (
	order_id INT(11) NOT NULL,
	item_id INT(11) NOT NULL,
	price INT(11) NOT NULL,
	amount INT(11) NOT NULL,
);