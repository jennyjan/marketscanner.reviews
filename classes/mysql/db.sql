DROP TABLE IF EXISTS b_market_scanner_review_elements;
CREATE table if not exists b_market_scanner_review_elements (
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
DROP TABLE IF EXISTS b_market_scanner_review_done;
CREATE table if not exists b_market_scanner_review_done (
    ID int(11) not null AUTO_INCREMENT,
    ELEMENT_ID int(11) not null,
    REVIEW_COUNT int(11),
    IS_DONE varchar(1),
    PRIMARY KEY (ID)
);