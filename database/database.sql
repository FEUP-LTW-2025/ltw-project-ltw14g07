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
	UserID INTEGER NOT NULL PRIMARY KEY,
	name TEXT NOT NULL,
	profilePicture TEXT,    --filepath, not sure how to actually store it
	email TEXT NOT NULL UNIQUE,
	password TEXT NOT NULL,   --should be hashed or something like that
	description TEXT,
	role TEXT CHECK (role IN ('client', 'freelancer', 'admin'))
);


CREATE TABLE Service (
	serviceID INTEGER NOT NULL PRIMARY KEY,
	userID INTEGER NOT NULL,
	title TEXT NOT NULL,
	description TEXT NOT NULL,
	hourlyRate INTEGER NOT NULL CHECK (hourlyRate >= 0),
	deliveryTime INTEGER CHECK(deliveryTime > 0),
	creationDate DATE NOT NULL DEFAULT CURRENT_DATE,

	FOREIGN KEY (userID) REFERENCES Users(userID)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);


CREATE TABLE Request (
	requestID INTEGER NOT NULL PRIMARY KEY,
	serviceID INTEGER NOT NULL,   --service to request
	userID INTEGER NOT NULL,      --user who requested
	title TEXT NOT NULL,
	notes TEXT,
	creationDate DATE NOT NULL,
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
	paymentID INTEGER PRIMARY KEY,
	requestID INTEGER NOT NULL,
	amount DECIMAL(10, 2),
	status TEXT CHECK (status IN ('pending', 'completed')),

	FOREIGN KEY (requestID) REFERENCES Request(requestID)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);


CREATE TABLE Comment (
	commentID INTEGER PRIMARY KEY,
	requestID INTEGER NOT NULL,   --comment on this request
	userID INTEGER NOT NULL,      --user who commented
	text TEXT, 
	creationDate DATE NOT NULL DEFAULT CURRENT_DATE,

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

