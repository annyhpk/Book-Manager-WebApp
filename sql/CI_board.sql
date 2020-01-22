CREATE TABLE CI_board (
board_id int(10) NOT NULL AUTO_INCREMENT,
board_pid int(10) NULL DEFAULT 0 ,
admin_id varchar(20) NULL ,
authors varchar(50) NOT NULL ,
subject varchar(50) NOT NULL ,
contents text NOT NULL ,
quantity INT(10) NOT NULL DEFAULT 0 ,
reg_date datetime NOT NULL ,
thumbnail varchar(300) NOT NULL,
datetime varchar(50) NOT NULL,
price varchar(30) NOT NULL,
url varchar(300) NOT NULL,
PRIMARY KEY (board_id),
INDEX board_pid (board_pid)
)
collate ='utf8_general_ci';