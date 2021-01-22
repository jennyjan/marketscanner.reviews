DROP TABLE IF EXISTS market_scanner_reviews_elements;
CREATE table if not exists market_scanner_reviews_elements (
    ID int(11) not null AUTO_INCREMENT,
    ELEMENT_ID int(11) not null,
    AUTHOR varchar(255),
    DATE DATE,
    COMMENT TEXT,
    POSITIVE TEXT,
    NEGATIVE TEXT,
    SCORE int(1) unsigned,
    MARKET_SCANER_REVIEW_ID TEXT,
    PRIMARY KEY (ID)
);
DROP TABLE IF EXISTS market_scanner_reviews_done;
CREATE table if not exists market_scanner_reviews_done (
    ID int(11) not null AUTO_INCREMENT,
    ELEMENT_ID int(11) not null,
    REVIEW_COUNT int(11),
    IS_DONE varchar(1),
    PRIMARY KEY (ID)
);