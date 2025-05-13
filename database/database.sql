PRAGMA foreign_keys = on;

DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Service;
DROP TABLE IF EXISTS Request;
DROP TABLE IF EXISTS Payment;
DROP TABLE IF EXISTS Comment;
DROP TABLE IF EXISTS Language;
DROP TABLE IF EXISTS Field;
DROP TABLE IF EXISTS ServiceLanguage;
DROP TABLE IF EXISTS ServiceField;


CREATE TABLE Users (
	UserID INTEGER  NOT NULL PRIMARY KEY,
	name TEXT NOT NULL,
	profilePicture TEXT,    --filepath, not sure how to actually store it
	email TEXT NOT NULL UNIQUE,
	password TEXT NOT NULL,   --should be hashed or something like that
	description TEXT,
	role TEXT CHECK (role IN ('client', 'freelancer', 'admin'))
);


CREATE TABLE Service (
	serviceID INTEGER  NOT NULL PRIMARY KEY,
	userID INTEGER NOT NULL,
	title TEXT NOT NULL,
	description TEXT NOT NULL,
	hourlyRate INTEGER NOT NULL CHECK (hourlyRate >= 0),
	deliveryTime INTEGER CHECK(deliveryTime > 0),
	creationDate DATE DEFAULT CURRENT_DATE,

	FOREIGN KEY (userID) REFERENCES Users(userID)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);


CREATE TABLE Request (
	requestID INTEGER  NOT NULL PRIMARY KEY,
	serviceID INTEGER NOT NULL,   --service to request
	userID INTEGER NOT NULL,      --user who requested
	title TEXT NOT NULL,
	description TEXT,
	creationDate DATE DEFAULT CURRENT_DATE,
	completionDate DATE,
	status TEXT NOT NULL CHECK(status IN ('pending', 'accepted', 'denied', 'done')),      --dar update para carrinho  
	review INTEGER CHECK (review BETWEEN 1 AND 5) DEFAULT NULL,

	FOREIGN KEY (serviceID) REFERENCES Service(serviceID)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY (userID) REFERENCES Users(userID)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE Payment (
	paymentID INTEGER  PRIMARY KEY,
	requestID INTEGER NOT NULL,
	amount DECIMAL(10, 2),
	status TEXT CHECK (status IN ('pending', 'completed')),

	FOREIGN KEY (requestID) REFERENCES Request(requestID)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);


CREATE TABLE Comment (
	commentID INTEGER  PRIMARY KEY,
	requestID INTEGER NOT NULL,   --comment on this request
	userID INTEGER NOT NULL,      --user who commented
	text TEXT, 
	creationDate DATE DEFAULT CURRENT_DATE,

	FOREIGN KEY (requestID) REFERENCES Request(requestID)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY (userID) REFERENCES Users(userID)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);


CREATE TABLE Language (
	language TEXT PRIMARY KEY
);

CREATE TABLE Field (
	field TEXT PRIMARY KEY
);



CREATE TABLE ServiceLanguage (  
	serviceID INTEGER,
	language TEXT,

	PRIMARY KEY (serviceID, language),

    FOREIGN KEY (serviceID) REFERENCES Service(serviceID)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY (language) REFERENCES Language(language)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE ServiceField (  
	serviceID INTEGER,
	field TEXT,

	PRIMARY KEY (serviceID, field),

	FOREIGN KEY (serviceID) REFERENCES Service(serviceID)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY (field) REFERENCES Field(field)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);



INSERT INTO USERS (UserID, name, email, password, description, role)
	VALUES (1, 'Roberto Céu', 'email@gmail.com', '123456', 'this is who i am', 'freelancer');

INSERT INTO Service (serviceID, userID, title, description, hourlyRate, deliveryTime) VALUES 
(1,
1,
'I will do modern mobile app ui ux design or website ui ux design',
'As a UI UX designer, I put much value on trustful, transparent, long-term relationships. Thats why Im very accurate in performing a professional approach. Your privacy, terms, and deadlines will always be respected. All I need to start is your specifications, a description of a problem you face, or just an initial idea of the future design product. But in case you are not sure at all - no problem. We will work out the products vision together, and I will provide you with fresh and unique ideas and efficient methods to create something outstanding and productive. I will manage your design project from start to final result. Feel free to contact me to discuss the details.', 
12, 
3
),

(2,
1,
'I will do modern mobile app ui ux design or website ui ux design',
'As a UI UX designer, I put much value on trustful, transparent, long-term relationships. Thats why Im very accurate in performing a professional approach. Your privacy, terms, and deadlines will always be respected. All I need to start is your specifications, a description of a problem you face, or just an initial idea of the future design product. But in case you are not sure at all - no problem. We will work out the products vision together, and I will provide you with fresh and unique ideas and efficient methods to create something outstanding and productive. I will manage your design project from start to final result. Feel free to contact me to discuss the details.', 
15, 
4
);



INSERT INTO Language VALUES
('PHP'),
('JavaScript'),
('Python'),
('TypeScript'),
('Ruby'),
('Java'),
('HTML'),
('CSS'),
('React'),
('SQL');

INSERT INTO Field VALUES
('UI/UX'),
('Game Development'),
('Neural Network'),
('Graphics Programming'),
('Cybersecurity'),
('Compiler'),
('Kernel'),
('Systems Programming'),
('Full Stack'),
('Backend');

INSERT INTO ServiceLanguage (serviceID, language) VALUES
(1, 'PHP'),
(1, 'CSS'),
(1, 'HTML'),

(2, 'PHP'),
(2, 'CSS'),
(2, 'HTML'),
(2, 'JavaScript'),
(2, 'SQL');

INSERT INTO ServiceField (serviceID, field) VALUES
(1, 'UI/UX'),

(2, 'UI/UX');


--CREATE TABLE ServiceLanguage (  
--	serviceID INTEGER,
--	language TEXT,
--
--	PRIMARY KEY (serviceID, language),
--
--    FOREIGN KEY (serviceID) REFERENCES Service(serviceID)
--		ON DELETE CASCADE
--		ON UPDATE CASCADE,
--	FOREIGN KEY (language) REFERENCES Language(language)
--		ON DELETE CASCADE
--		ON UPDATE CASCADE
--);
--
--CREATE TABLE ServiceField (  
--	serviceID INTEGER,
--	field TEXT,
--
--	PRIMARY KEY (serviceID, field),
--
--	FOREIGN KEY (serviceID) REFERENCES Service(serviceID)
--		ON DELETE CASCADE
--		ON UPDATE CASCADE,
--	FOREIGN KEY (field) REFERENCES Field(field)
--		ON DELETE CASCADE
--		ON UPDATE CASCADE
--);