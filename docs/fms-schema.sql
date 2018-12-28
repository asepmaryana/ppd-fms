CREATE TABLE service_group
(
	id INT NOT NULL AUTO_INCREMENT,
	code VARCHAR(5) NOT NULL,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(code)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE transport_company
(
	id INT NOT NULL AUTO_INCREMENT,
	code VARCHAR(5) NOT NULL,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(code)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE manufacture_company
(
	id INT NOT NULL AUTO_INCREMENT,
	code VARCHAR(5) NOT NULL,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(code)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE line_type
(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(name)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE route_line
(
	id INT NOT NULL AUTO_INCREMENT,
	code VARCHAR(5) NOT NULL,
	name VARCHAR(255) NOT NULL,
	line_type_id INT,
	PRIMARY KEY(id),
	UNIQUE(code),
	FOREIGN KEY(line_type_id) REFERENCES line_type(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE bus_device
(
	id INT NOT NULL AUTO_INCREMENT,
	code VARCHAR(5) NOT NULL,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(code)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE operation_status
(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(name)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE connection_status
(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(name)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE alarm_status
(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(name)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE service_status
(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(name)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE bus
(
	id INT NOT NULL AUTO_INCREMENT,
	code VARCHAR(10) NOT NULL,
	name VARCHAR(255) NOT NULL,
	car_number VARCHAR(12) NOT NULL,
	reg_date DATE,
	made_date DATE,
	model_no VARCHAR(20),
	icon VARCHAR(255),
	remark VARCHAR(255),
	latitude DOUBLE,
	longitude DOUBLE,
	speed INT,
	max_speed INT,
	software_version VARCHAR(10),
	bus_device_id INT,
	service_group_id INT,
	route_line_id INT,
	manufacture_company_id INT,
	transport_company_id INT,
	service_status_id INT,
	operation_status_id INT,
	connection_status_id INT,
	alarm_status_id INT,
	start_time DATETIME,
	last_time DATETIME,
	PRIMARY KEY(id),
	UNIQUE(code),
	FOREIGN KEY(bus_device_id) REFERENCES bus_device(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(service_group_id) REFERENCES service_group(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(route_line_id) REFERENCES route_line(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(manufacture_company_id) REFERENCES manufacture_company(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(transport_company_id) REFERENCES transport_company(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(service_status_id) REFERENCES service_status(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(operation_status_id) REFERENCES operation_status(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(connection_status_id) REFERENCES connection_status(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(alarm_status_id) REFERENCES alarm_status(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE driver
(
	id INT NOT NULL AUTO_INCREMENT,
	code VARCHAR(10) NOT NULL,
	name VARCHAR(255) NOT NULL,
	first_name VARCHAR(255),
	last_name VARCHAR(255),
	part_name VARCHAR(255),
	telp VARCHAR(20),
	phone VARCHAR(20),
	fax VARCHAR(16),
	email VARCHAR(255),
	transport_company_id INT,
	service_group_id INT,
	service_status_id INT,
	PRIMARY KEY(id),	
	UNIQUE(code),
	FOREIGN KEY(transport_company_id) REFERENCES transport_company(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(service_group_id) REFERENCES service_group(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(service_status_id) REFERENCES service_status(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE ad_screen
(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(name)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE station_direct
(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(name)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE station_type
(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(name)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE station
(
	id INT NOT NULL AUTO_INCREMENT,
	code VARCHAR(10) NOT NULL,
	name VARCHAR(255) NOT NULL,
	short_name VARCHAR(255),
	reg_date DATE,
	made_date DATE,
	install_date DATE,
	install_address VARCHAR(255),
	remark VARCHAR(255),
	line_order_no INT,
	latitude DOUBLE,
	longitude DOUBLE,
	route_line_id INT,
	station_direct_id INT,
	station_type_id INT,
	ad_screen_id INT,
	service_group_id INT,
	service_status_id INT,
	PRIMARY KEY(id),
	UNIQUE(code),
	FOREIGN KEY(route_line_id) REFERENCES route_line(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(station_direct_id) REFERENCES station_direct(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(station_type_id) REFERENCES station_type(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(ad_screen_id) REFERENCES ad_screen(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(service_group_id) REFERENCES service_group(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(service_status_id) REFERENCES service_status(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE transparency
(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(name)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE thick
(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(name)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE line
(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	lati_long TEXT,
	color VARCHAR(7),
	PRIMARY KEY(id),
	UNIQUE(name)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE route_map
(
	id INT NOT NULL AUTO_INCREMENT,
	reg_date DATETIME,
	map_name VARCHAR(255),
	map_address VARCHAR(255),
	map_no VARCHAR(10),
	display_no VARCHAR(3),	
	thick_id INT,
	transparency_id INT,
	service_group_id INT,
	service_status_id INT,
	PRIMARY KEY(id),
	FOREIGN KEY(thick_id) REFERENCES thick(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(transparency_id) REFERENCES transparency(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(service_group_id) REFERENCES service_group(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY(service_status_id) REFERENCES service_status(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE route_map_line
(
	id INT NOT NULL AUTO_INCREMENT,
	route_map_id INT,
	line_id INT,
	PRIMARY KEY(id),
	UNIQUE(route_map_id, line_id),
	FOREIGN KEY(route_map_id) REFERENCES route_map(id) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY(line_id) REFERENCES line(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE route_line_route_map
(
	id INT NOT NULL AUTO_INCREMENT,
	route_line_id INT,
	route_map_id INT,
	PRIMARY KEY(id),
	UNIQUE(route_line_id, route_map_id),
	FOREIGN KEY(route_line_id) REFERENCES route_line(id) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY(route_map_id) REFERENCES route_map(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE speed_violation_log
(
	id VARCHAR(36) NOT NULL,
	reg_date_time DATETIME NOT NULL,
	bus_id INT NOT NULL,
	speed INT,
	PRIMARY KEY(id),
	FOREIGN KEY(bus_id) REFERENCES bus(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE route_deviation_log
(
	id VARCHAR(36) NOT NULL,
	reg_date_time DATETIME NOT NULL,
	bus_id INT NOT NULL,
	distance INT,
	PRIMARY KEY(id),
	FOREIGN KEY(bus_id) REFERENCES bus(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE operation_distance
(
	id VARCHAR(36) NOT NULL,
	reg_date DATE,
	bus_id INT NOT NULL,
	distance INT,
	PRIMARY KEY(id),
	FOREIGN KEY(bus_id) REFERENCES bus(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE bus_driving_log
(
	id VARCHAR(36) NOT NULL,
	reg_date_time DATETIME,
	bus_id INT NOT NULL,
	latitude DOUBLE,
	longitude DOUBLE,
	speed INT,
	distance INT,
	PRIMARY KEY(id),
	FOREIGN KEY(bus_id) REFERENCES bus(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE garage
(
	id INT NOT NULL AUTO_INCREMENT,
	place VARCHAR(255) NOT NULL,
	latitude DOUBLE,
	longitude DOUBLE,
	PRIMARY KEY(id),
	UNIQUE(place)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE station_pass_log
(
	id VARCHAR(36) NOT NULL,
	reg_date_time DATETIME,
	station_id INT,
	bus_id INT,
	PRIMARY KEY(id),
	FOREIGN KEY(station_id) REFERENCES station(id) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY(bus_id) REFERENCES bus(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE config
(
	id INT NOT NULL AUTO_INCREMENT,
	code VARCHAR(255) NOT NULL,
	label VARCHAR(255),
	val VARCHAR(255),
	PRIMARY KEY(id),
	UNIQUE(code)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE config_email
(
	smtp_host VARCHAR(255),
	smtp_port VARCHAR(255),
	smtp_user VARCHAR(255),
	smtp_account VARCHAR(255),
	smtp_pass VARCHAR(255),
	smtp_timeout TINYINT NOT NULL DEFAULT 10,
	mailtype  VARCHAR(255),
	wordwrap TINYINT(1) NOT NULL DEFAULT 1,
	charset VARCHAR(255) DEFAULT 'iso-8859-1'
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE authority
(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(name)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE users
(
	id VARCHAR(36) NOT NULL,
	username VARCHAR(255) NOT NULL,
	password VARCHAR(255) NOT NULL,
	firstname VARCHAR(255) NOT NULL,
	lastname VARCHAR(255),
	foto VARCHAR(255),
	phone VARCHAR(255),
	active CHAR DEFAULT 'Y',
	PRIMARY KEY(id),
	UNIQUE(username)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE users_authority
(
	users_id VARCHAR(36) NOT NULL, 
	authority_id INT NOT NULL,
	FOREIGN KEY(users_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY(authority_id) REFERENCES authority(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `keys`
(
	id INT NOT NULL AUTO_INCREMENT,
	user_id VARCHAR(36) NOT NULL,
	`key` VARCHAR(40) NOT NULL,
	`level` INT(2) NOT NULL,
	ignore_limits TINYINT(1) NOT NULL DEFAULT '0',
	is_private_key TINYINT(1)  NOT NULL DEFAULT '0',
	ip_addresses TEXT NULL DEFAULT NULL,
	date_created INT(11) NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY(user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;