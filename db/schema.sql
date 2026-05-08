
CREATE DATABASE IF NOT EXISTS neb_computer;
USE neb_computer;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) UNIQUE,
  grade TINYINT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_active TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS chapters (
  id INT AUTO_INCREMENT PRIMARY KEY,
  grade TINYINT NOT NULL,
  chapter_number TINYINT NOT NULL,
  title VARCHAR(200) NOT NULL,
  content MEDIUMTEXT,
  summary TEXT,
  UNIQUE KEY uniq_chapter (grade, chapter_number)
);

CREATE TABLE IF NOT EXISTS quiz_questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  grade TINYINT NOT NULL,
  chapter TINYINT NOT NULL,
  difficulty ENUM('easy','medium','hard') DEFAULT 'easy',
  question TEXT NOT NULL,
  option_a VARCHAR(255) NOT NULL,
  option_b VARCHAR(255) NOT NULL,
  option_c VARCHAR(255) NOT NULL,
  option_d VARCHAR(255) NOT NULL,
  correct_option TINYINT NOT NULL,
  explanation TEXT,
  is_active TINYINT(1) DEFAULT 1
);

CREATE TABLE IF NOT EXISTS past_questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  grade TINYINT NOT NULL,
  year_bs SMALLINT NOT NULL,
  topic VARCHAR(120) NOT NULL,
  question TEXT NOT NULL,
  answer TEXT NOT NULL,
  memory_trick TEXT,
  marks INT DEFAULT 5,
  is_important TINYINT(1) DEFAULT 0
);

CREATE TABLE IF NOT EXISTS quiz_attempts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  student_name VARCHAR(120) NOT NULL,
  grade TINYINT NOT NULL,
  chapter TINYINT NOT NULL,
  score INT NOT NULL,
  total_questions INT NOT NULL,
  attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_attempt_grade (grade)
);

CREATE TABLE IF NOT EXISTS student_progress (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  grade TINYINT NOT NULL,
  chapter TINYINT NOT NULL,
  completion TINYINT NOT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_progress (user_id, grade, chapter)
);

CREATE TABLE IF NOT EXISTS leaderboard (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_name VARCHAR(120) NOT NULL,
  grade TINYINT NOT NULL,
  chapter VARCHAR(20) NOT NULL,
  score INT NOT NULL,
  recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS page_views (
  id INT AUTO_INCREMENT PRIMARY KEY,
  path VARCHAR(255) NOT NULL,
  viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (username, password_hash)
VALUES ('admin', '$2y$10$YD9Q5GWpZ6YG1ChcxrFAouq2XNNpJBWlAbIYW/W2PASi6DPd7OJbW')
ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash);

INSERT INTO chapters (grade, chapter_number, title, summary) VALUES
(11,1,'Computer System','Introduction to computer systems and generations'),
(11,2,'Number System','Number bases, conversion and complements'),
(11,3,'Boolean Logic','Logic gates and Boolean algebra'),
(11,4,'Computer Software','System and application software'),
(11,5,'Computer Memory','Memory hierarchy and storage'),
(11,6,'Data Communication and Networking','Network models and topologies'),
(11,7,'Security, Ethics, and Legal Issues','Cybersecurity and legal issues'),
(11,8,'Database Management System','DBMS fundamentals and SQL'),
(11,9,'Multimedia','Multimedia components and applications'),
(11,10,'Programming in C','C programming basics'),
(12,1,'Advanced DBMS','Transactions and advanced SQL'),
(12,2,'Advanced Networking','IP addressing and routing'),
(12,3,'Web Technology I','HTML, CSS, JavaScript'),
(12,4,'Web Technology II','PHP and MySQL'),
(12,5,'Advanced C Programming','Advanced C topics'),
(12,6,'Object-Oriented Programming (C++)','OOP principles and C++'),
(12,7,'Software Engineering','SDLC and software quality'),
(12,8,'Recent Trends','AI, Cloud, IoT and blockchain')
ON DUPLICATE KEY UPDATE title=VALUES(title), summary=VALUES(summary);

INSERT INTO quiz_questions (grade, chapter, difficulty, question, option_a, option_b, option_c, option_d, correct_option, explanation) VALUES
(11,1,'easy','Which generation used vacuum tubes?','First','Second','Third','Fourth',0,'First generation used vacuum tubes.'),
(11,2,'easy','Binary of decimal 10 is?','1001','1010','1110','1100',1,'10 in binary is 1010.'),
(11,3,'easy','NOT 1 equals?','0','1','2','-1',0,'NOT inverts logic level.'),
(11,4,'easy','Which is system software?','MS Word','Operating System','Photoshop','Browser',1,'OS is system software.'),
(11,5,'easy','Fastest memory is?','RAM','ROM','Cache','HDD',2,'Cache is fastest among options.'),
(11,6,'easy','LAN stands for?','Local Area Network','Large Area Network','Line Access Network','Local Access Node',0,'LAN=Local Area Network.'),
(11,7,'easy','Firewall is used for?','Cooking','Security','Printing','Storage',1,'Firewall filters traffic.'),
(11,8,'easy','Primary key is?','Duplicate field','Unique identifier','Foreign reference','Null value',1,'Primary key uniquely identifies row.'),
(11,9,'easy','Which is multimedia element?','Audio','Only text','Only numbers','Only logic',0,'Audio is multimedia element.'),
(11,10,'easy','C source file extension is?','.cpp','.py','.c','.java',2,'.c is C source extension.'),
(12,1,'medium','ACID property A means?','Atomicity','Accuracy','Alignment','Aggregation',0,'A=Atomicity.'),
(12,2,'medium','IPv6 address size is?','32-bit','64-bit','128-bit','256-bit',2,'IPv6 uses 128 bits.'),
(12,3,'easy','Semantic tag in HTML5?','<b>','<section>','<font>','<center>',1,'section is semantic tag.'),
(12,4,'easy','PHP variable starts with?','#','$','@','%',1,'PHP variables start with $.'),
(12,5,'medium','malloc is used for?','File read','Dynamic memory','Sorting','Looping',1,'malloc allocates dynamic memory.'),
(12,6,'medium','Inheritance is OOP concept of?','Data hiding','Reusability','Compilation','Linking',1,'Inheritance provides reusability.'),
(12,7,'easy','SDLC phase first is?','Testing','Deployment','Requirement analysis','Maintenance',2,'Requirement analysis starts SDLC.'),
(12,8,'easy','Cloud model SaaS means?','Software as a Service','Storage as a Service','Security as a System','System as a Software',0,'SaaS=Software as a Service.'),
(11,1,'medium','ALU stands for?','Arithmetic Logic Unit','Automated Logic Unit','Arithmetic Linear Unit','Array Logic Unit',0,'ALU performs arithmetic/logical operations.'),
(12,1,'hard','Which normalization removes multivalued dependencies?','1NF','2NF','3NF','4NF',3,'4NF removes multivalued dependencies.');

INSERT INTO past_questions (grade, year_bs, topic, question, answer, memory_trick, marks, is_important) VALUES
(11,2075,'Software','What is difference between compiler and interpreter?','Compiler translates whole program; interpreter line-by-line.','Book vs live translator',5,1),
(11,2077,'Networking','Explain OSI model layers.','Physical, Data Link, Network, Transport, Session, Presentation, Application.','Please Do Not Throw Sausage Pizza Away',8,1),
(11,2078,'Number System','Convert (10110101)2 to decimal/octal/hex.','Decimal 181, Octal 265, Hex B5.','Group by 3/4 bits',5,1),
(11,2079,'Boolean Logic','Explain De Morgan theorem.','(A+B)''=A''.B'' and (A.B)''=A''+B''.','Swap operator and invert',5,1),
(11,2080,'C Programming','Write C program for factorial using recursion.','Use recursive function factorial(n)=n*factorial(n-1).',NULL,5,1),
(12,2078,'DBMS','Explain ACID properties of transactions.','Atomicity, Consistency, Isolation, Durability.',NULL,8,1),
(12,2079,'Networking','Explain subnetting with example.','Divide network into smaller subnets using mask.',NULL,8,1),
(12,2080,'Web Tech','Explain PHP GET and POST methods.','GET sends data in URL; POST in body.',NULL,5,1),
(12,2077,'OOP','Define polymorphism in C++.','Same interface, different implementation.',NULL,5,1),
(12,2076,'Recent Trends','Explain cloud service models.','IaaS, PaaS, SaaS with examples.',NULL,5,1);
