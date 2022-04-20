/* Create table user*/
CREATE TABLE user (
    id        INTEGER       PRIMARY KEY AUTOINCREMENT,
    firstname VARCHAR (255),
    lastname  VARCHAR (255),
    email     VARCHAR (255) UNIQUE,
    password  VARCHAR (255),
    roles     TEXT
);

/* Create table hotel*/
CREATE TABLE hotel (
    id          INTEGER       PRIMARY KEY AUTOINCREMENT,
    name        VARCHAR (255),
    description TEXT,
    address     TEXT,
    city        VARCHAR (255),
    slug        VARCHAR (255),
    owner_id                  REFERENCES user (id) 
                              UNIQUE
);

/* Create table image*/
CREATE TABLE image (
    id       INTEGER       PRIMARY KEY AUTOINCREMENT,
    name     VARCHAR (255),
	suite_id INTEGER  
);

/* Create table suite*/
CREATE TABLE suite (
    id                INTEGER       PRIMARY KEY AUTOINCREMENT,
    hotel_id          INTEGER       REFERENCES hotel (id),
    title             VARCHAR (255),
    description       TEXT,
    booking_link      VARCHAR (255),
    price             INTEGER,
    featured_image_id INTEGER       UNIQUE
                                    REFERENCES image (id) 
);

/* Drop table image, then create again with a foreign key */
DROP TABLE image;
CREATE TABLE image (
    id       INTEGER       PRIMARY KEY AUTOINCREMENT,
    name     VARCHAR (255),
    suite_id INTEGER       REFERENCES suite (id) 
);

/* Create table booking*/
CREATE TABLE booking (
    id          INTEGER       PRIMARY KEY AUTOINCREMENT,
    hotel_id    INTEGER       REFERENCES hotel (id),
    suite_id    INTEGER       REFERENCES suite (id),
    user_id     INTEGER       REFERENCES user (id),
    begin_at    DATE,
    end_at      DATE,
    total_price INTEGER       NOT NULL,
    status      VARCHAR (255) 
);



