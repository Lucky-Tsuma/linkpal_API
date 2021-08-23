create database linkpal2.0;

	create table specialty (
		specialty_id int unsigned auto_increment primary key,
		specialty_name varchar(30)
		);

	insert into specialty(specialty_name) values ("plumbing"),("cleaning services"),("gardening"),("painting"),("laundry"),("wiring"),("water supply"),("masonry"),("carpentry"),
		("welding and fabrication"),("tailoring"),("electric repair"),("manicure and pedicure"), ("photography"), ("salon (women)"), ("salon (men)"), ("other");


		create table users (
			user_id int unsigned auto_increment,
			firstname varchar(15) not null,
			lastname varchar(15) not null,
			phone_number varchar(13) not null,
			longitude varchar(20) not null,
			latitude varchar(20) not null,
			passwrd varchar(100) not null,
			gender varchar(1) not null,
			join_date varchar(13),
			primary key(user_id, phone_number)
			);

		create table users_extras (
			phone_number varchar(13),
			user_specialty int unsigned,
			summary tinytext,
			profile_pic varchar(100),
			portfolio varchar(100),
			primary key(phone_number),
			foreign key(phone_number) references users(phone_number) on delete cascade,
			foreign key(user_specialty) references specialty(specialty_id) on delete set null
			);

		create table jobs (
			job_id int unsigned auto_increment,
			user_id int unsigned,
			job_description tinytext not null,
			post_date varchar(13),
			job_specialty int unsigned,
			primary key(job_id, user_id),
			foreign key(user_id) references users(user_id) on delete cascade,
			foreign key(job_specialty) references specialty(specialty_id) on delete set null
			);

		create table job_requests (
			user_id int unsigned,
			job_id int unsigned,
			bidding_amount int(10),
			proposal tinytext,
			request_date varchar(13),
			primary key(job_id, user_id),
			foreign key(user_id) references users(user_id) on delete cascade,
			foreign key(job_id) references jobs(job_id) on delete cascade
			);

		create table job_invitations (
			worker_id int unsigned,
			employer_id int unsigned,
			invite_date varchar(13),
			primary key (worker_id, employer_id),
			foreign key(worker_id) references users(user_id) on delete cascade,
			foreign key(employer_id) references users(user_id) on delete cascade
			);

		create table work_ratings (
			job_id int unsigned,
			worker_id int unsigned,
			employer_id int unsigned,
			rating double(1, 1),
			job_start_date timestamp,
			primary key(job_id, worker_id, employer_id),
			foreign key(job_id) references jobs(job_id) on delete cascade,
			foreign key(worker_id) references users(user_id) on delete cascade,
			foreign key(employer_id) references users(user_id) on delete cascade
			);


/*In the case that a foreign key constraint fails, make sure to index the column in the referenced table first, then proceed*/
