INSERT INTO authority(name) VALUES ('Operator'),('Admin');

INSERT INTO users(id,username,password,firstname) VALUES
	('1','operator',MD5('operator'),'Operator'),
	('2','admin',MD5('admin'),'Administrator');

INSERT INTO users_authority(users_id,authority_id) VALUES 
	('1', 1), ('2', 2);
	
INSERT INTO operation_status(name) VALUES 
	('Drive'),('Stop'),('Off-Line');

INSERT INTO connection_status(name) VALUES 
	('ON'), ('OFF');
	
INSERT INTO alarm_status(name) VALUES 
	('Good'), ('Warning');
	
INSERT INTO service_status(name) VALUES 
	('Enable'), ('Disable');
	
INSERT INTO station_direct(name) VALUES 
	('UP'), ('DOWN');
	
INSERT INTO station_type(name) VALUES 
	('Real Station'), ('Major Point');
	
INSERT INTO line_type(name) VALUES 
	('Back <--> Forth'), ('Circulation');