CREATE TABLE tx_nitsanproduct_domain_model_product (
	name varchar(255) NOT NULL DEFAULT '',
	description text NOT NULL DEFAULT '',
	image varchar(255) NOT NULL DEFAULT '',
	price varchar(255) NOT NULL DEFAULT '',
	brands int(11) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE tx_nitsanproduct_domain_model_brand (
	product int(11) unsigned DEFAULT '0' NOT NULL,
	name varchar(255) NOT NULL DEFAULT '',
	image varchar(255) NOT NULL DEFAULT ''
);
