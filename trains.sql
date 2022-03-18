CREATE DATABASE trains;
USE trains;

CREATE TABLE `line`
(
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `name`      varchar(20) NOT NULL,
    `createdAt` timestamp   NOT NULL DEFAULT current_timestamp(),
    `updatedAt` timestamp   NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
);

CREATE TABLE `routes`
(
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `line_id`   int(11)     NOT NULL,
    `name`      varchar(20) NOT NULL,
    `createdAt` timestamp   NOT NULL DEFAULT current_timestamp(),
    `updatedAt` timestamp   NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
);

CREATE TABLE `route_operator`
(
    `id`          int(11)     NOT NULL AUTO_INCREMENT,
    `route_id`    int(11)     NOT NULL,
    `operator_id` varchar(20) NOT NULL,
    `createdAt`   timestamp   NOT NULL DEFAULT current_timestamp(),
    `updatedAt`   timestamp   NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `operator_id` (`operator_id`)
);

CREATE TABLE `runs`
(
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `route_id`  int(11)     NOT NULL,
    `name`      varchar(20) NOT NULL,
    `createdAt` timestamp   NOT NULL DEFAULT current_timestamp(),
    `updatedAt` timestamp   NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
);
