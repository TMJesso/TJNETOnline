<?php
require_once(LIB_PATH.DS.'config.php');
class MySQLDatabase extends DatabaseObject {
	private $connection;
	
	function __construct() {
		$this->open_connection();
		$this->create_tables();
	}
	
	public function open_connection() {
		//$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT, DB_SOCKET);
		if (mysqli_connect_errno()) {
			die ("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")" );
		}
	}
	
	public function close_connection() {
		if (isset($this->connection)) {
			mysqli_close($this->connection);
			unset($this->connection);
		}
	}
	
	public function query($sql) {
		$result = mysqli_query( $this->connection, $sql);
		// test if there was a query error
		//$this->confirm_query($result);
		return $result;
	}
	
	private function confirm_query($result) {
		if (!$result) {
			die( "Database query failed.");
		}
	}
	
	public function prevent_injection($string) {
		$escaped_string = mysqli_real_escape_string($this->connection, $string);
		return $escaped_string;
	}
	
	// "database neutral" functions
	public function fetch_array($result_set) {
		return mysqli_fetch_array($result_set);
	}
	
	public function num_rows($result_set) {
		return mysqli_num_rows($result_set);
	}
	
	public function insert_id() {
		// get the last id inserted over the current db connection
		return mysqli_insert_id($this->connection);
	}
	
	public function affected_rows() {
		return mysqli_affected_rows($this->connection);
	}
	
	public function find_subject_by_id($subject_id) {
	
		$safe_subject_id = $this->prevent_injection($subject_id);
	
		$sql  = "SELECT * ";
		$sql .= "FROM subjects ";
		$sql .= "WHERE id = {$safe_subject_id} ";
		$sql .= "LIMIT 1";
		$result_set = mysqli_query($this->connection, $sql);
		$this->confirm_query($result_set);
		if ($subject = mysqli_fetch_assoc($result_set)) {
			return $subject;
		} else {
			return null;
		}
	}
	
	public function does_subject_have_pages($subject_id) {

		$sql = "SELECT * from pages WHERE subject_id = {$subject_id} ORDER BY position";
		$result_set = mysqli_query($this->connection, $sql);
		$this->confirm_query($result_set);
		return $result_set;
	}
	
	function find_pages_on_subject($subject_id) {
		$safe_subject_id = $this->prevent_injection($subject_id);
		
		$sql = "SELECT * ";
		$sql .= "FROM pages ";
		$sql .= "WHERE subject_id = {$safe_subject_id} ";
		$result_set = mysqli_query($this->connection, $sql);
		$this->confirm_query($result_set);
		if ($pages = mysqli_fetch_assoc($result_set)) {
			return $pages;
		} else {
			return null;
		}
	}
	
	public function find_page_by_id($page_id) {
		$safe_page_id = $this->prevent_injection($page_id);
		
		$sql  = "SELECT * ";
		$sql .= "FROM pages ";
		$sql .= "WHERE id = {$safe_page_id} ";
		$sql .= "LIMIT 1";
		$result_set = mysqli_query($this->connection, $sql);
		$this->confirm_query($result_set);
		if ($page = mysqli_fetch_assoc($result_set)) {
			return $page;
		} else {
			return null;
		}
	}
	
	
	public function find_selected_page() {
		global $current_subject;
		global $current_page;
	
		if (isset($_GET["subject"])) {
			$current_subject = $this->find_subject_by_id($_GET["subject"]);
			$current_page = null;
		} elseif (isset($_GET["page"])) {
			$current_page = $this->find_page_by_id($_GET["page"]);
			$current_subject = null;
		} else {
			$current_page = null;
			$current_subject = null;
		}
	}

	public function find_selected_photo() {
		global $current_photo;
	
		if (isset($_GET["photo"])) {
			$current_photo = $this->find_photo($_GET["photo"]);
		} else {
			$current_photo = null;
		}
	}
	
	public function find_photo($id) {
		$safe_id = $this->prevent_injection($id);
		
		$sql  = "SELECT * ";
		$sql .= " FROM photo ";
		$sql .= " WHERE id = {$safe_id} ";
		$sql .= " LIMIT 1";
		$result_set = mysqli_query($this->connection, $sql);
		$this->confirm_query($result_set);
		if ($photo = mysqli_fetch_assoc($result_set)) {
			return $photo;
		} else {
			return null;
		}
	}
	
	public function find_photo_by_id($page_id) {
		$safe_page_id = $this->prevent_injection($page_id);
	
		$sql  = "SELECT * ";
		$sql .= "FROM photo ";
		$sql .= "WHERE page_id = {$safe_page_id} LIMIT 1";
		$result_set = mysqli_query($this->connection, $sql);
		$this->confirm_query($result_set);
		if ($photo = mysqli_fetch_assoc($result_set)) {
			return $photo;
		} else {
			return null;
		}
	}
	
	private function create_tables() {
		$menu  = "CREATE TABLE IF NOT EXISTS menu ( ";
		$menu .= "id INT(11) NOT NULL AUTO_INCREMENT, ";
		$menu .= "menu_name VARCHAR(30) NOT NULL, ";
		$menu .= "loc VARCHAR(30) NOT NULL, ";
		$menu .= "position INT(3) NOT NULL, ";
		$menu .= "level INT(1) NOT NULL, ";
		$menu .= "visible TINYINT(1) NOT NULL, ";
		$menu .= "admin TINYINT(1) NOT NULL, ";
		$menu .= "PRIMARY KEY (id), ";
		$menu .= "INDEX (position ASC))";
		if ($this->query($menu)) {
			if ($this->check_tables("menu") == 0) {
				$this->menuloaddata();
			}
		}
		
		$user  = "CREATE TABLE IF NOT EXISTS users ( ";
		$user .= "id INT(11) NOT NULL AUTO_INCREMENT, ";
		$user .= "username VARCHAR(16) NOT NULL, ";
		$user .= "password VARCHAR(72) NOT NULL, ";
		$user .= "first_name VARCHAR(30) NOT NULL, ";
		$user .= "last_name VARCHAR(30) NOT NULL, ";
		$user .= "PRIMARY KEY (id))";
		if ($this->query($user)) {
			if ($this->check_tables("users") == 0) {
				$this->usersloaddata();
			}
		}
		
		$sub  = "CREATE TABLE IF NOT EXISTS subjects ( ";
		$sub .= "id INT(11) NOT NULL AUTO_INCREMENT, ";
		$sub .= "menu_name VARCHAR(30) NOT NULL, ";
		$sub .= "loc VARCHAR(30) NOT NULL, ";
		$sub .= "position INT(3) NOT NULL, ";
		$sub .= "level INT(1) NOT NULL, ";
		$sub .= "visible TINYINT(1) NOT NULL, ";
		$sub .= "admin TINYINT(1) NOT NULL, ";
		$sub .= "PRIMARY KEY (id), ";
		$sub .= "INDEX (position ASC))";
		if ($this->query($sub)) {
			if ($this->check_tables("subjects") == 0) {
				$this->subjectloaddata();
			}
		}
		
		$page  = "CREATE TABLE IF NOT EXISTS pages ( ";
		$page .= "id INT(11) NOT NULL AUTO_INCREMENT, ";
		$page .= "subject_id INT(11) NOT NULL, ";
		$page .= "name VARCHAR(30) NOT NULL, ";
		$page .= "menu_name VARCHAR(30) NOT NULL, ";
		$page .= "loc VARCHAR(30) NOT NULL, ";
		$page .= "position INT(3) NOT NULL, ";
		$page .= "level INT(1) NOT NULL, ";
		$page .= "visible TINYINT(1) NOT NULL, ";
		$page .= "content TEXT, ";
		$page .= "PRIMARY KEY (id), ";
		$page .= "INDEX (subject_id ASC), ";
		$page .= "INDEX (position ASC))";
		if ($this->query($page)) {
			if ($this->check_tables("pages") == 0) {
				$this->pagesloaddata();
			}
		}
		
		$photo  = "CREATE TABLE IF NOT EXISTS photos ( ";
		$photo .= "id INT(11) NOT NULL AUTO_INCREMENT, ";
		$photo .= "page_id INT(11) NOT NULL, ";
		$photo .= "position INT(3) NOT NULL, ";
		$photo .= "filename VARCHAR(255) NOT NULL, ";
		$photo .= "type VARCHAR(100) NOT NULL, ";
		$photo .= "size INT(11) NOT NULL, ";
		$photo .= "caption VARCHAR(255) NOT NULL, ";
		$photo .= "PRIMARY KEY (id), ";
		$photo .= "INDEX (page_id ASC), ";
		$photo .= "INDEX (position ASC) )";
		if ($this->query($photo)) {
			if ($this->check_tables("photos") == 0) {
				$this->photosloaddata();
			}
		}
	}
	
	private function count_table($table) {
		$sql  = "SELECT COUNT(*) from " . $table;
		$result = $this->query($sql);
		$row = $this->fetch_array($result);
		return array_shift($row);
	}
	
	private function check_tables($table) {
		return (int) $this->count_table($table);
	}
	
	private function menudumpdata() {
		// menu

		$filename="../backup/menu.txt";
		
		$sql = "SELECT * FROM menu INTO OUTFILE {$filename}";
		mysqli_query($this->connection, $sql);
	}
	
	private function usersdumpdata() {
		// users
		
		$filename="../backup/users.txt";
		
		$sql = "SELECT * FROM users INTO OUTFILE {$filename}";
		mysqli_query($this->connection, $sql);
	}

	private function subjectsdumpdata() {
		// subjects
		
		$filename='subjects.txt';
		
		$sql = "SELECT * FROM subjects INTO OUTFILE {$filename}";
		mysqli_query($this->connection, $sql);
	}
	
	private function pagesdumpdata() {
		// pages
		
		$filename="../backup/pages.txt";
		
		$sql = "SELECT * FROM pages INTO OUTFILE {$filename}";
		mysqli_query($this->connection, $sql);
		//$this->confirm_query($result);
	}

	private function photodumpdata() {
		// photo
		
		$filename = "../../../../www/tjnet/backup/photo.txt";
		
		$sql = "SELECT * FROM photo INTO OUTFILE 'photo.txt'";
		echo $sql . "<br/>";
		$result = mysqli_query($this->connection, $sql);
		return $result;
	}
	
	public function backuptables() {
		//$this->menudumpdata();
		//$this->usersdumpdata();
		//$this->subjectsdumpdata();
		//$this->pagesdumpdata();
		if ($this->photodumpdata()) {
			return true;
		}	
	}
	
	private function usersloaddata() {
		$sql  = "INSERT INTO users ";
		$sql .= "(username, password, first_name, last_name) ";
		
		$sql .= "VALUES ";
		
		$sql .= "('TJAdmin', '" . password_encript('TJAdmin', 'kM8FuFowdvRGi8Xq') . "', 'Theral Jessop', 'Admin')";
		$this->query($sql);
	}
	
	private function subjectloaddata() {
		$sql  = "INSERT INTO subjects ";
		$sql .= "(menu_name, loc, position, level, visible, admin) ";
		$sql .= "VALUES ";
		$sql .= "('Index', 				'manage_content.php?subject=1', 1, 1, 1, 0), ";
		$sql .= "('Resume', 			'manage_content.php?subject=2', 2, 1, 1, 0), ";
		$sql .= "('Enroll', 			'manage_content.php?subject=3', 3, 1, 1, 0), ";
		$sql .= "('Accomplishments', 	'manage_content.php?subject=4', 4, 1, 1, 0), ";
		$sql .= "('Contact',			'manage_content.php?subject=5', 5, 1, 1, 0), ";
		$sql .= "('Salary Guide', 		'manage_content.php?subject=6', 6, 2, 1, 0), ";
		$sql .= "('Test', 				'manage_content.php?subject=7', 7, 2, 1, 0)";
		$this->query($sql);
		
	}
	
	private function pagesloaddata() {
		$sql  = "INSERT INTO pages ";
		$sql .= "(subject_id, name, menu_name, loc, position, level, visible, content) ";
		
		$sql .= "VALUES ";
		
		$sql .= "(1,'','My Pic','manage_content.php?page=1',1,1,1,'&lt;div class=&quot;mainimg&quot;  &gt;&lt;img style=&quot;width:100px; height:100px;&quot; src=&quot;media/mypic_wolf_resting.jpg&quot;  alt=&quot;My picture&quot; title=&quot;My picture&quot;/&gt;&lt;/div&gt;'), ";
		$sql .= "(1,'','Timex Sinclair 1000','manage_content.php?page=2',2,1,1,'&lt;p&gt;In 1982 I bought my first computer, a &lt;a href=&quot;timex.php&quot; title=&quot;My first computer&quot;&gt;Timex/Sinclair 1000&lt;/a&gt; with 2K memory and I also bought the 16K memory expansion pack.  This computer used the Basic programming language and worked on a regular TV.  It also had the capability to save the programs to a standard cassette recorder.&lt;/p&gt;'), ";
		$sql .= "(1,'','Callville Bay','http://www.callvillebay.com/',3,1,1,'&lt;p&gt;I graduated from the 8th grade, which was the highest grade level at our school. I was given the choice to go to a private high school or go to work. I went to work with my dad and worked several miscellaneous jobs until 1985 when our community received the approval from the state educational board to establish the public high school.  It was phased in over a 4 year period and I went back to school as a high school freshman when I was 21.&lt;br/&gt;&lt;br/&gt;One year after that I was offered a job by my dad to work in &lt;a href=&quot;http://callvillebay.com/&quot; target=&quot;_blank&quot; title=&quot;I worked here for 2 years&quot;&gt;Callville Bay&lt;/a&gt; on Lake Mead.'), ";
		$sql .= "(1,'','Kaypro 4','manage_content.php?page=3',4,1,1,'  We worked there for one year and then I returned home to go to work for the school as the Audio/Visual Technician Assistant.  It was there that I began programming in dBase II using a &lt;a href=&quot;kaypro_4.html&quot; title=&quot;I used this for 1 year&quot;&gt;Kaypro 4&lt;/a&gt; then dBase III. That was a good job for several years until the budget began to shrink and I was laid off.&lt;/p&gt;'), ";
		$sql .= "(1,'','Inspriation','manage_content.php?pages=4',5,1,1,'&lt;p&gt;My best friend, who owned a computer shop, introduced me to &lt;span title=&quot;I learned programming using Foxpro&trade; up to version 2.6&quot;&gt;Foxpro&lt;/span&gt;&trade; and I began learning programming using &lt;span title=&quot;I learned programming using Foxpro&trade; up to version 2.6&quot;&gt;Foxpro&lt;/span&gt;&trade;. &lt;span title=&quot;I learned programming using Foxpro&trade; up to version 2.6&quot;&gt;Foxpro&lt;/span&gt;&trade&; would run circles around the dBase platform. We worked on several projects together and I also began working on projects on my own&period;  I wrote a program for the local private university to keep track of students&comma; instructors&comma; classes and schedules&period;  I wrote my own password routine that would encrypt the password with the ability to recover in the event that the password was lost or forgotten.&lt;/p&gt;&lt;p&gt;In early \'99 I moved to Littlefield, AZ and began working for a internet company out of Mesquite, Nevada.  I also started smoking. In October 2000 I left Mesquite, Nevada and moved to Indiana and in November started working for Swifty Gas.  I worked there receiving several promotions and in 2002 I was promoted to manager and worked at several of their stations. In October 2004 I left Swifty Gas.&lt;/p&gt;&lt;p&gt;I went to work for Porter Engineered Systems through a temporary service because I didn\'t have my GED.  In December of 2004 I finished my GED and received my GED diploma in January of 2005 and I began my employment with Porter Engineered Systems.  In January 2008 I took a very serious look at my life and quit smoking and found myself searching for the truth through fasting and prayer.  In 2010 I began attending church and in 2012 I became a member of &lt;a href=&quot;http://mormon.org/&quot; target=&quot;_blank&quot; style=&quot;text-decoration:none; color:#336633;&quot; title=&quot;I became a member in 2012&quot;&gt;The Church of Jesus Christ of Latter-day Saints&lt;/a&gt;.'), ";
		$sql .= "(1,'Ivy Tech Community College','Ivy Tech','manage_content.php?page=5',6,1,1,'In the meantime&comma; in 2010 I enrolled at &lt;a href&equals;&quot;http&colon;&sol;&sol;ivytech&period;edu&sol;&quot; target&equals;&quot;&lowbar;blank&quot; title&equals;&quot;I have 2 degrees from Ivy Tech&quot;&gt;Ivy Tech Community College&lt;&sol;a&gt; and graduated with an AS in Agriculture in 2012&period;  I re-enrolled again and in Fall of 2013 I graduated with AAS in Accounting&period;&lt;&sol;p&gt;'), ";
		$sql .= "(1,'Indiana University Kokomo','IUK','http://www.iuk.edu/',7,1,1,'&lt;&sol;fieldset&gt;&lt;p&gt;In the Spring of 2014 I enrolled at &lt;a href&equals;&quot;http&colon;&sol;&sol;iuk&period;edu&sol;&quot; target&equals;&quot;&lowbar;blank&quot; &gt;Indiana University Kokomo&lt;&sol;a&gt;&period;&lt;br &sol;&gt;&lt;br &sol;&gt;I have a graduation date for May 2016&excl;&lt;br&sol;&gt;&lt;&sol;p&gt;'), ";
		$sql .= "(2,'','Summary','manage_content.php?page=7',1,1,1,'&apos;&lt;h2 title&equals;&quot;Summary of Qualifications&quot;&gt;&lt;span&gt;Summary of Qualifications&lt;&sol;span&gt;&lt;&sol;h2&gt;&lt;fieldset&gt;&lt;legend title&equals;&quot;Summary&quot;&gt;Summary&lt;&sol;legend&gt;&lt;ul&gt;&lt;li&gt;Working knowledge of computers and programming&lt;&sol;li&gt;&lt;li&gt;Working knowledge of JavaScript&comma; HTML5&comma; CSS3&comma; Visual Basic&comma; Java&lt;&sol;li&gt;&lt;li&gt;Windows experience&lt;&sol;li&gt;&lt;li&gt;Purpose driven with strong record of reaching and exceeding organization goals set forth by administration&lt;&sol;li&gt;&lt;li&gt;Committed to the quality and quantity that team work provides&lt;&sol;li&gt;&lt;li&gt;Ensuring a high level of customer service and satisfaction&lt;&sol;li&gt;&lt;&sol;ul&gt;&lt;&sol;fieldset&gt;'), ";
		$sql .= "(2,'','Skills','manage_content.php?page=8',2,1,1,'&lt;h2 title&equals;&quot;Skills&quot;&gt;&lt;span&gt;Skills&lt;&sol;span&gt;&lt;&sol;h2&gt;&lt;fieldset&gt;&lt;legend title&equals;&quot;Skills&quot;&gt;Skills&lt;&sol;legend&gt;&lt;table style&equals;&quot;text-align&colon;left&semi;&quot;&gt;&lt;tr&gt;&lt;td&gt;&lt;ul&gt;&lt;li&gt;Computer knowledge&lt;&sol;li&gt;&lt;li&gt;Knowledgeable of Microsoft Office Suite&lt;&sol;li&gt;&lt;li&gt;Programming logic&lt;&sol;li&gt;&lt;li&gt;Database Management systems using dBase and Foxpro platforms&lt;&sol;li&gt;&lt;&sol;ul&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;ul&gt;&lt;li&gt;Quick learner and flexible&lt;&sol;li&gt;&lt;li&gt;Persistent with overcoming challenges&lt;&sol;li&gt;&lt;li&gt;Customer support &amp; HR&lt;&sol;li&gt;&lt;li&gt;Java Development&lt;&sol;li&gt;&lt;li&gt;PHP and dynamic web development with mySQL&lt;&sol;li&gt;&lt;&sol;ul&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;&sol;table&gt;&lt;&sol;fieldset&gt;'), ";
		$sql .= "(2,'Indiana University Kokomo','Education 1','manage_content.php?page=9',3,1,1,'&lt;h2 title&equals;&quot;Education&quot;&gt;Education&lt;&sol;h2&gt;&lt;fieldset&gt;&lt;legend title&equals;&quot;Indiana University Kokomo&quot;&gt;Indiana University Kokomo&lt;&sol;legend&gt;&lt;table&gt;&lt;tr&gt;&lt;td class&equals;&quot;leftjustify&quot;&gt;&lt;strong&gt;Bachelor of Science in Informatics &lpar;Computer Studies&rpar;&lt;&sol;strong&gt;&lt;br&sol;&gt;&lt;ul&gt;&lt;li&gt;&lt;a href&equals;&quot;http&colon;&sol;&sol;newsroom&period;iuk&period;edu&sol;articles&sol;2015&sol;01-jan&sol;more-than-500-students-earn-fall-deans-list-honors-at-iu-kokomo&period;php&quot; target&equals;&quot;&lowbar;blank&quot;&gt;Fall 2014 Dean&bsol;&apos;s List&lt;&sol;a&gt;&lt;&sol;li&gt;&lt;li&gt;Spring 2015 GPA 3&period;584&lt;br&sol;&gt;&lpar;unofficial transcript&rpar;&lt;&sol;li&gt;&lt;&sol;ul&gt;&lt;&sol;td&gt;&lt;td class&equals;&quot;rightjustify&quot;&gt;Senior Status&lt;br&sol;&gt;Spring 2013 &amp;&num;8213&semi; current&lt;br&sol;&gt;Expected Graduation May 2016&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td class&equals;&quot;leftjustify&quot;&gt;Courses completed include&colon;&lt;ul&gt;&lt;li&gt;Information Infrastructure I &lpar;Visual Basic&rpar;&lt;span class&equals;&quot;mustindicator&quot;&gt;&ast;&lt;&sol;span&gt;&lt;&sol;li&gt;&lt;li&gt;Intro to Web Scripting &lpar;JavaScript&rpar;&lt;&sol;li&gt;&lt;li&gt;Intermediate Website Design Principles and Practices&lt;&sol;li&gt;&lt;li&gt;Web-design using HTML5&comma; CSS3&comma; JavaScript and PHP with mySQL&lt;&sol;li&gt;&lt;li&gt;Social Informatics&lt;&sol;li&gt;&lt;li&gt;Introduction to Informatics&lt;&sol;li&gt;&lt;li&gt;Information Infrastructure II &lpar;Java&rpar;&lt;span class&equals;&quot;mustindicator&quot;&gt;&ast;&lt;&sol;span&gt;&lt;&sol;li&gt;&lt;li&gt;Math Foundations of Informatics&lt;&sol;li&gt;&lt;li&gt;Design &amp; Development of an Information System&lt;&sol;li&gt;&lt;li&gt;HCI &sol; Interaction Design&lt;&sol;li&gt;&lt;li&gt;Management Information Systems&lt;&sol;li&gt;&lt;li&gt;Managing &amp; Behavior in Organizaion&lt;&sol;li&gt;&lt;&sol;ul&gt;&lt;p&gt;&lt;span class&equals;&quot;mustindicator&quot;&gt;&ast;&lt;&sol;span&gt; coding examples available upon request&lt;&sol;p&gt;&lt;&sol;td&gt;&lt;td class&equals;&quot;leftjustify&quot;&gt;Courses in progress&colon;&lt;br &sol;&gt;&lt;ul&gt;&lt;li&gt;Financial Management&lt;&sol;li&gt;&lt;li&gt;The Art of Comics&lt;&sol;li&gt;&lt;li&gt;Information Representation&lt;&sol;li&gt;&lt;li&gt;Design and Develop of an Info System II&lt;&sol;li&gt;&lt;&sol;ul&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;&sol;table&gt;&lt;&sol;fieldset&gt;'), ";
		$sql .= "(2,'Ivy Tech Community College','Education 2','manage_content.php?page=10',4,1,1,'&lt;h2 title&equals;&quot;Education&quot;&gt;Education&lt;&sol;h2&gt;&lt;fieldset&gt;&lt;legend title&equals;&quot;Ivy Tech Community College&quot;&gt;Ivy Tech Community College&lt;&sol;legend&gt;&lt;table&gt;&lt;tr&gt;&lt;td class&equals;&quot;leftjustify&quot;&gt;&lt;&sol;td&gt;&lt;td class&equals;&quot;rightjustify&quot;&gt;Kokomo&comma; IN&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td class&equals;&quot;leftjustify&quot;&gt;&lt;strong&gt;Associate of Science in Agriculture&lt;&sol;strong&gt;&lt;&sol;td&gt;&lt;td class&equals;&quot;rightjustify&quot;&gt;Completed 2012&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;2&quot;&gt;&lt;ul&gt;&lt;li&gt;Member of Phi Theta Kappa Academic Honors Society&lt;&sol;li&gt;&lt;li&gt;Member of the Diversity Student Union&lt;&sol;li&gt;&lt;li&gt;Dean&bsol;&apos;s List recipient multiple semesters with a cumulative GPA of 3&period;5&lt;&sol;li&gt;&lt;&sol;ul&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td class&equals;&quot;leftjustify&quot;&gt;&lt;strong&gt;Associate of Applied Science in Accounting&lt;&sol;strong&gt;&lt;&sol;td&gt;&lt;td class&equals;&quot;rightjustify&quot;&gt;Completed 2013&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;2&quot; class&equals;&quot;leftjustify&quot;&gt;Coursework completed includes&colon;&lt;br&sol;&gt;&lt;ul&gt;&lt;li&gt;Accounting and Introduction to Business&lt;&sol;li&gt;&lt;li&gt;Microsoft Excel Certification&lt;&sol;li&gt;&lt;li&gt;Payroll&lt;&sol;li&gt;&lt;li&gt;Bookkeeping&lt;&sol;li&gt;&lt;&sol;ul&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;&sol;table&gt;&lt;&sol;fieldset&gt;'), ";
		$sql .= "(2,'Continental Inc','Company 1','manage_content.php?page=11',5,1,1,'&lt;h2 title&equals;&quot;Professional Experience&quot;&gt;Professional Experience&lt;&sol;h2&gt;&lt;fieldset&gt;&lt;legend title&equals;&quot;Continental Inc&quot;&gt;Continental Inc&lt;&sol;legend&gt;&lt;table&gt;&lt;tr&gt;&lt;td class&equals;&quot;leftjustify&quot;&gt;&lt;&sol;td&gt;&lt;td class&equals;&quot;rightjustify&quot;&gt;Anderson&comma; IN&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td class&equals;&quot;leftjustify&quot;&gt;&lt;strong&gt;General Labor&lt;&sol;strong&gt;&lt;&sol;td&gt;&lt;td class&equals;&quot;rightjustify&quot;&gt;2015 - current&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;2&quot;&gt;&lt;ul&gt;&lt;li&gt;Worked at the Smithfield plant in Peru&comma; Indiana&lt;&sol;li&gt;&lt;li&gt;Varied work oppurtunities within the plant&lt;&sol;li&gt;&lt;li&gt;Willingness to learn and work in any role&lt;&sol;li&gt;&lt;&sol;ul&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;&sol;table&gt;&lt;&sol;fieldset&gt;'), ";
		$sql .= "(2,'Porter Engineered Systems','Company 2','manage_content.php?page=12',6,1,1,'&lt;h2 title&equals;&quot;Professional Experience&quot;&gt;Professional Experience&lt;&sol;h2&gt;&lt;fieldset&gt;&lt;legend title&equals;&quot;Porter Engineered Systems&quot;&gt;Porter Engineered Systems&lt;&sol;legend&gt;&lt;table&gt;&lt;tr&gt;&lt;td class&equals;&quot;leftjustify&quot;&gt;&lt;&sol;td&gt;&lt;td class&equals;&quot;rightjustify&quot;&gt;Westfield&comma; IN&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td class&equals;&quot;leftjustify&quot;&gt;&lt;strong&gt;Preventive Maintenance Technician &amp;&num;8213&semi; Daily Line Startup &lpar;2007-2008&rpar;&lt;&sol;strong&gt;&lt;&sol;td&gt;&lt;td class&equals;&quot;rightjustify&quot;&gt;2005-2008&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;2&quot;&gt;&lt;ul&gt;&lt;li&gt;Responsible for the daily startup of the line for the assembly team&lt;&sol;li&gt;&lt;li&gt;Maintained and repaired machine break downs &lpar;limited&rpar;&lt;&sol;li&gt;&lt;li&gt;Effectively communicated with supervisor and subordinates&lt;&sol;li&gt;&lt;li&gt;High level of proficiency and detail in final inspection station&lt;&sol;li&gt;&lt;&sol;ul&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;&sol;table&gt;&lt;&sol;fieldset&gt;'), ";
		$sql .= "(2,'Staffing Resources','Company 3','manage_content.php?page=13',7,1,1,'&lt;h2 title&equals;&quot;Professional Experience&quot;&gt;Professional Experience&lt;&sol;h2&gt;&lt;fieldset&gt;&lt;legend title&equals;&quot;Staffing Resources&quot;&gt;Staffing Resources&lt;&sol;legend&gt;&lt;table&gt;&lt;tr&gt;&lt;td class&equals;&quot;leftjustify&quot;&gt;&lt;&sol;td&gt;&lt;td class&equals;&quot;rightjustify&quot;&gt;Kokomo&comma; IN&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td class&equals;&quot;leftjustify&quot;&gt;&lt;strong&gt;General labor&lt;&sol;strong&gt;&lt;&sol;td&gt;&lt;td class&equals;&quot;rightjustify&quot;&gt;2004-2005&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;2&quot;&gt;&lt;ul&gt;&lt;li&gt;Due to high level of productivity&semi; was 3&sol;30 individuals that was retained by Porter Engineered Systems&lt;&sol;li&gt;&lt;li&gt;Worked temporary for Park 100 Foods through Staffing Resources agency assisting processes throughout the facility following all policies and procedures of the company including health and safety regulations&lt;&sol;li&gt;&lt;li&gt;Worked temporary for Porter Engineered Systems through Staffing Resources agency accurately completing final quality inspections&lt;&sol;li&gt;&lt;&sol;ul&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;&sol;table&gt;&lt;&sol;fieldset&gt;'), ";
		$sql .= "(2,'Swifty Gas Company','Company 4','manage_content.php?page=',8,1,1,'&lt;h2 title&equals;&quot;Professional Experience&quot;&gt;Professional Experience&lt;&sol;h2&gt;&lt;fieldset&gt;&lt;legend title&equals;&quot;Swifty Gas Company&quot;&gt;Swifty Gas Company&lt;&sol;legend&gt;&lt;table&gt;&lt;tr&gt;&lt;td class&equals;&quot;leftjustify&quot;&gt;&lt;&sol;td&gt;&lt;td class&equals;&quot;rightjustify&quot;&gt;Elwood&comma; IN&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td class&equals;&quot;leftjustify&quot;&gt;&lt;strong&gt;Manager &lpar;Elwood 2003-2004&rpar;&lt;&sol;strong&gt;&lt;&sol;td&gt;&lt;td class&equals;&quot;rightjustify&quot;&gt;2000-2004&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;2&quot; class&equals;&quot;leftjustify&quot;&gt;&lt;strong&gt;Manager &lpar;Alexandria&colon; 2002-2003&rpar;&lt;&sol;strong&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;2&quot; class&equals;&quot;leftjustify&quot;&gt;&lt;strong&gt;Assistant Manager &lpar;2001-2002&rpar;&lt;&sol;strong&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;2&quot;&gt;&lt;ul&gt;&lt;li&gt;Maintained inventory with product ordering&comma; receiving and stocking&lt;&sol;li&gt;&lt;li&gt;Provided customer support&lt;&sol;li&gt;&lt;li&gt;Filled customer orders in a timely manner&lt;&sol;li&gt;&lt;li&gt;Created weekly employee scheduling&lt;&sol;li&gt;&lt;li&gt;Position demanded employees to work outside tolerating all weather types and standing for long periods of time&lt;&sol;li&gt;&lt;&sol;ul&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;&sol;table&gt;&lt;&sol;fieldset&gt;'), ";
		$sql .= "(3,'Indiana University Kokomo','Current Classes Enrolled','manage_content.php?page=15',1,1,1,'&lt;h1&gt;IUK Major&colon; Informatics&lt;&sol;h1&gt;&lt;h2&gt;Current Classes Enrolled&lt;&sol;h2&gt;&lt;fieldset&gt;&lt;legend&gt;Spring 2016 - January 11 through May 6&lt;&sol;legend&gt;&lt;table&gt;&lt;tr&gt;&lt;th style&equals;&quot;text-align&colon;left&semi;&quot;&gt;Class&lt;&sol;th&gt;&lt;th style&equals;&quot;text-align&colon;left&semi;&quot;&gt;Name&lt;&sol;th&gt;&lt;th&gt;When&lt;&sol;th&gt;&lt;th&gt;Credits&lt;&sol;th&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi;&quot;&gt;INFO 451&lt;&sol;td&gt;&lt;td style&equals;&quot;text-align&colon;left&semi;&quot;&gt;Design &amp; Development of an Information System II&lt;&sol;td&gt;&lt;td&gt;Tu &amp; Th&lt;&sol;td&gt;&lt;td&gt;3&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr style&equals;&quot;text-align&colon;center&semi;&quot;&gt;&lt;td style&equals;&quot;text-align&colon;left&semi;&quot;&gt;BUS-F 301&lt;&sol;td&gt;&lt;td style&equals;&quot;text-align&colon;left&semi;&quot;&gt;Financial Management&lt;&sol;td&gt;&lt;td&gt;Tu &amp; Th&lt;&sol;td&gt;&lt;td&gt;3&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi;&quot;&gt;FINA-A 280&lt;&sol;td&gt;&lt;td style&equals;&quot;text-align&colon;left&semi;&quot;&gt;The Art of Comics&lt;&sol;td&gt;&lt;td&gt;WEB&lt;&sol;td&gt;&lt;td&gt;3&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi;&quot;&gt;INFO-I 308&lt;&sol;td&gt;&lt;td style&equals;&quot;text-align&colon;left&semi;&quot;&gt;Information Representation&lt;&sol;td&gt;&lt;td&gt;Mo &amp; We&lt;&sol;td&gt;&lt;td&gt;3&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;&sol;table&gt;&lt;br &sol;&gt;&lt;br &sol;&gt;&lt;&sol;fieldset&gt;'), ";
		$sql .= "(3,'Indiana University Kokomo','Spring 2014','manage_content.php?page=16',2,1,1,'&lt;h2&gt;Classes I have taken at IUK&lt;&sol;h2&gt;&lt;fieldset&gt;&lt;legend&gt;Spring 2014&lt;&sol;legend&gt;&lt;table&gt;&lt;tr style&equals;&quot;text-decoration&colon;underline&semi;&quot;&gt;&lt;th style&equals;&quot;text-align&colon;left&semi; width&colon;100px&semi;&quot;&gt;Class&lt;&sol;th&gt;&lt;th class&equals;&quot;tblName&quot;&gt;Name&lt;&sol;th&gt;&lt;th&gt;Credits&lt;&sol;th&gt;&lt;th&gt;Grade&lt;&sol;th&gt;&lt;th&gt;Grade points&lt;&sol;th&gt;&lt;th&gt;Total points&lt;&sol;th&gt;&lt;th&gt;Total Grade Points&lt;&sol;th&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi; font-size&colon;&period;75em&semi;&quot;&gt;INFO 101&lt;&sol;td&gt;&lt;td class&equals;&quot;tblName&quot;&gt;Introduction to Informatics&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014credit1&quot; readonly value&equals;&quot;4&period;00&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014grade1&quot; readonly value&equals;&quot;A&plus;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014points1&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014classGPA1&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014totalPoints1&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi; font-size&colon;&period;75em&semi;&quot;&gt;INFO 202&lt;&sol;td&gt;&lt;td class&equals;&quot;tblName&quot;&gt;Social Informatics&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014credit2&quot; readonly value&equals;&quot;3&period;00&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014grade2&quot; readonly value&equals;&quot;B&plus;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014points2&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014classGPA2&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014totalPoints2&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi; font-size&colon;&period;75em&semi;&quot;&gt;PHIL 100&lt;&sol;td&gt;&lt;td class&equals;&quot;tblName&quot;&gt;Introduction to Philosophy&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014credit3&quot; readonly value&equals;&quot;3&period;00&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014grade3&quot; readonly value&equals;&quot;B&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014points3&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014classGPA3&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014totalPoints3&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;2&quot; class&equals;&quot;rightbold&quot;&gt;&lt;br &sol;&gt;Total Credits&lt;&sol;td&gt;&lt;td&gt;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&lt;br &sol;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014sumTotalCredits&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td colspan&equals;&quot;1&quot; class&equals;&quot;rightbold&quot;&gt;&lt;br &sol;&gt;Total Points&colon;&lt;&sol;td&gt;&lt;td&gt;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&lt;br &sol;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014sumPoints&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td colspan&equals;&quot;1&quot; class&equals;&quot;rightbold&quot;&gt;&lt;br &sol;&gt;Total Grade Points&colon;&lt;&sol;td&gt;&lt;td&gt;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&lt;br &sol;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014sumTotalPoints&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;5&quot; class&equals;&quot;rightbold&quot;&gt;Average Grade Points&colon;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014avgGradePoints&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;5&quot; class&equals;&quot;rightbold&quot;&gt;Average Letter Grade&colon;&lt;&sol;td&gt;&lt;td class&equals;&quot;centerbold&quot;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2014txtGPA&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;&sol;table&gt;&lt;&sol;fieldset&gt;'), ";
		$sql .= "(3,'Indiana University Kokomo','Fall 2014','manage_content.php?page=17',3,1,1,'&lt;fieldset&gt;&lt;legend&gt;Fall 2014&lt;&sol;legend&gt;&lt;table&gt;&lt;tr style&equals;&quot;text-decoration&colon;underline&semi;&quot;&gt;&lt;th style&equals;&quot;text-align&colon;left&semi; width&colon;100px&semi;&quot;&gt;Class&lt;&sol;th&gt;&lt;th class&equals;&quot;tblName&quot;&gt;Name&lt;&sol;th&gt;&lt;th&gt;Credits&lt;&sol;th&gt;&lt;th&gt;Grade&lt;&sol;th&gt;&lt;th&gt;Grade points&lt;&sol;th&gt;&lt;th&gt;Total points&lt;&sol;th&gt;&lt;th&gt;Total Grade Points&lt;&sol;th&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi; font-size&colon;&period;75em&semi;&quot;&gt;INFO 210&lt;&sol;td&gt;&lt;td class&equals;&quot;tblName&quot;&gt;Information Infrastructure I&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014credit1&quot; readonly value&equals;&quot;4&period;00&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014grade1&quot; readonly value&equals;&quot;A&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014points1&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014classGPA1&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014totalPoints1&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi; font-size&colon;&period;75em&semi;&quot;&gt;INFO 213&lt;&sol;td&gt;&lt;td class&equals;&quot;tblName&quot;&gt;Website Design &amp;amp&semi; Development&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014credit2&quot; readonly value&equals;&quot;3&period;00&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014grade2&quot; readonly value&equals;&quot;A&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014points2&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014classGPA2&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014totalPoints2&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi; font-size&colon;&period;75em&semi;&quot;&gt;NMCM 262&lt;&sol;td&gt;&lt;td class&equals;&quot;tblName&quot;&gt;Intro to Web Scripting&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014credit3&quot; readonly value&equals;&quot;3&period;00&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014grade3&quot; readonly value&equals;&quot;B&plus;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014points3&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014classGPA3&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014totalPoints3&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi; font-size&colon;&period;75em&semi;&quot;&gt;NMCM 345&lt;&sol;td&gt;&lt;td class&equals;&quot;tblName&quot;&gt;Interm Website Design Principles &amp;amp&semi; Practice&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014credit4&quot; readonly value&equals;&quot;3&period;00&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014grade4&quot; readonly value&equals;&quot;A&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014points4&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014classGPA4&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014totalPoints4&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;2&quot; class&equals;&quot;rightbold&quot;&gt;&lt;br &sol;&gt;Total Credits&lt;&sol;td&gt;&lt;td&gt;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&lt;br &sol;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014sumTotalCredits&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td colspan&equals;&quot;1&quot; class&equals;&quot;rightbold&quot;&gt;&lt;br &sol;&gt;Total Points&colon;&lt;&sol;td&gt;&lt;td&gt;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&lt;br &sol;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014sumPoints&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td colspan&equals;&quot;1&quot; class&equals;&quot;rightbold&quot;&gt;&lt;br &sol;&gt;Total Grade Points&colon;&lt;&sol;td&gt;&lt;td&gt;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&lt;br &sol;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014sumTotalPoints&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;5&quot; class&equals;&quot;rightbold&quot;&gt;Grade Point Average&colon;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014avgGradePoints&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;5&quot; class&equals;&quot;rightbold&quot;&gt;Overall Letter Grade&colon;&lt;&sol;td&gt;&lt;td class&equals;&quot;centerbold&quot;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2014txtGPA&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;&sol;table&gt;&lt;&sol;fieldset&gt;'), ";
		$sql .= "(3,'Indiana University Kokomo','Spring 2015','manage_content.php?page=18',4,1,1,'&lt;fieldset&gt;&lt;legend&gt;Spring 2015&lt;&sol;legend&gt;&lt;table&gt;&lt;tr style&equals;&quot;text-decoration&colon;underline&semi;&quot;&gt;&lt;th style&equals;&quot;text-align&colon;left&semi; width&colon;100px&semi;&quot;&gt;Class&lt;&sol;th&gt;&lt;th class&equals;&quot;tblName&quot;&gt;Name&lt;&sol;th&gt;&lt;th&gt;Credits&lt;&sol;th&gt;&lt;th&gt;Grade&lt;&sol;th&gt;&lt;th&gt;Grade points&lt;&sol;th&gt;&lt;th&gt;Total points&lt;&sol;th&gt;&lt;th&gt;Total Grade Points&lt;&sol;th&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi; font-size&colon;&period;75em&semi;&quot;&gt;INFO 211&lt;&sol;td&gt;&lt;td class&equals;&quot;tblName&quot;&gt;Information Infrastructure II - Java&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015credit1&quot; readonly value&equals;&quot;4&period;00&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015grade1&quot; readonly value&equals;&quot;A&plus;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015points1&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015classGPA1&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015totalPoints1&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi; font-size&colon;&period;75em&semi;&quot;&gt;INFO 201&lt;&sol;td&gt;&lt;td class&equals;&quot;tblName&quot;&gt;Math Foundations of Informatics &lt;span class&equals;&quot;mustindicator&quot;&gt;&ast;&lt;&sol;span&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015credit2&quot; readonly value&equals;&quot;4&period;00&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015grade2&quot; readonly value&equals;&quot;B-&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015points2&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015classGPA2&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015totalPoints2&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi; font-size&colon;&period;75em&semi;&quot;&gt;INFO 303&lt;&sol;td&gt;&lt;td class&equals;&quot;tblName&quot;&gt;Organizational Informatics&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015credit3&quot; readonly value&equals;&quot;3&period;00&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015grade3&quot; readonly value&equals;&quot;A-&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015points3&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015classGPA3&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015totalPoints3&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi; font-size&colon;&period;75em&semi;&quot;&gt;INFO 356&lt;&sol;td&gt;&lt;td class&equals;&quot;tblName&quot;&gt;Globalization&comma; Where We Fit In&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015credit4&quot; readonly value&equals;&quot;3&period;00&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015grade4&quot; readonly value&equals;&quot;B&plus;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015points4&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015classGPA4&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015totalPoints4&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;2&quot; class&equals;&quot;rightbold&quot;&gt;&lt;br &sol;&gt;Total Credits&lt;&sol;td&gt;&lt;td&gt;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&lt;br &sol;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015sumTotalCredits&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td colspan&equals;&quot;1&quot; class&equals;&quot;rightbold&quot;&gt;&lt;br &sol;&gt;Total Points&colon;&lt;&sol;td&gt;&lt;td&gt;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&lt;br &sol;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015sumPoints&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td colspan&equals;&quot;1&quot; class&equals;&quot;rightbold&quot;&gt;&lt;br &sol;&gt;Total Grade Points&colon;&lt;&sol;td&gt;&lt;td&gt;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&lt;br &sol;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015sumTotalPoints&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;5&quot; class&equals;&quot;rightbold&quot;&gt;Average Grade Points&colon;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015avgGradePoints&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;5&quot; class&equals;&quot;rightbold&quot;&gt;Average Letter Grade&colon;&lt;&sol;td&gt;&lt;td class&equals;&quot;centerbold&quot;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;s2015txtGPA&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;&sol;table&gt;&lt;&sol;fieldset&gt;'), ";
		$sql .= "(3,'Indiana University Kokomo','Fall 2015','manage_content.php?page=19',5,1,1,'&lt;fieldset&gt;&lt;legend&gt;Fall 2015&lt;&sol;legend&gt;&lt;table&gt;&lt;tr style&equals;&quot;text-decoration&colon;underline&semi;&quot;&gt;&lt;th style&equals;&quot;text-align&colon;left&semi; width&colon;100px&semi;&quot;&gt;Class&lt;&sol;th&gt;&lt;th class&equals;&quot;tblName&quot;&gt;Name&lt;&sol;th&gt;&lt;th&gt;Credits&lt;&sol;th&gt;&lt;th&gt;Grade&lt;&sol;th&gt;&lt;th&gt;Grade points&lt;&sol;th&gt;&lt;th&gt;Total points&lt;&sol;th&gt;&lt;th&gt;Total Grade Points&lt;&sol;th&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi; font-size&colon;&period;75em&semi;&quot;&gt;INFO 450&lt;&sol;td&gt;&lt;td class&equals;&quot;tblName&quot;&gt;Design &amp;amp&semi; Development of an Information System&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015credit1&quot; readonly value&equals;&quot;3&period;00&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015grade1&quot; readonly value&equals;&quot;B&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015points1&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015classGPA1&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015totalPoints1&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi; font-size&colon;&period;75em&semi;&quot;&gt;INFO 300&lt;&sol;td&gt;&lt;td class&equals;&quot;tblName&quot;&gt;HCI &amp;sol&semi; Interaction Design&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015credit2&quot; readonly value&equals;&quot;3&period;00&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015grade2&quot; readonly value&equals;&quot;B&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015points2&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015classGPA2&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015totalPoints2&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi; font-size&colon;&period;75em&semi;&quot;&gt;BUS-S 302&lt;&sol;td&gt;&lt;td class&equals;&quot;tblName&quot;&gt;Management Information Systems&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015credit3&quot; readonly value&equals;&quot;3&period;00&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015grade3&quot; readonly value&equals;&quot;A&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015points3&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015classGPA3&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015totalPoints3&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td style&equals;&quot;text-align&colon;left&semi; font-size&colon;&period;75em&semi;&quot;&gt;BUS-Z 302&lt;&sol;td&gt;&lt;td class&equals;&quot;tblName&quot;&gt;Managing &amp;amp&semi; Behavior in Organization&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015credit4&quot; readonly value&equals;&quot;3&period;00&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015grade4&quot; readonly value&equals;&quot;C&plus;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015points4&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015classGPA4&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015totalPoints4&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;2&quot; class&equals;&quot;rightbold&quot;&gt;&lt;br &sol;&gt;Total Credits&lt;&sol;td&gt;&lt;td&gt;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&lt;br &sol;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015sumTotalCredits&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td colspan&equals;&quot;1&quot; class&equals;&quot;rightbold&quot;&gt;&lt;br &sol;&gt;Total Points&colon;&lt;&sol;td&gt;&lt;td&gt;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&lt;br &sol;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015sumPoints&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td colspan&equals;&quot;1&quot; class&equals;&quot;rightbold&quot;&gt;&lt;br &sol;&gt;Total Grade Points&colon;&lt;&sol;td&gt;&lt;td&gt;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&equals;&lt;br &sol;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015sumTotalPoints&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;5&quot; class&equals;&quot;rightbold&quot;&gt;Average Grade Points&colon;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015avgGradePoints&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td colspan&equals;&quot;5&quot; class&equals;&quot;rightbold&quot;&gt;Average Letter Grade&colon;&lt;&sol;td&gt;&lt;td class&equals;&quot;centerbold&quot;&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;f2015txtGPA&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;td&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;&sol;table&gt;&lt;&sol;fieldset&gt;'), ";
		$sql .= "(3,'Indiana University Kokomo','IUK Overall','manage_content.php?page=20',6,1,1,'&lt;fieldset&gt;&lt;legend&gt;IUK Overall&lt;&sol;legend&gt;&lt;table&gt;&lt;tr&gt;&lt;td class&equals;&quot;rightbold&quot;&gt;Average Grade Points&colon;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;overallavgGradePoints&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;tr&gt;&lt;td class&equals;&quot;rightbold&quot;&gt;Average Letter Grade&colon;&lt;&sol;td&gt;&lt;td&gt;&lt;input class&equals;&quot;txtCenter&quot; id&equals;&quot;overalltxtGPA&quot; readonly value&equals;&quot;&quot; &sol;&gt;&lt;&sol;td&gt;&lt;&sol;tr&gt;&lt;&sol;table&gt;&lt;&sol;fieldset&gt;'), ";
		$sql .= "(6,'','2015 Salary Center','manage_content.php?page=21',2,1,1,'&lt;h2 title&equals;&quot;Summary of Qualifications&quot;&gt;&lt;span&gt;2015 Salary Center&lt;&sol;span&gt;&lt;&sol;h2&gt;&lt;fieldset&gt;&lt;legend title&equals;&quot;Summary&quot;&gt;Past Salary Trends&lt;&sol;legend&gt;&lt;ul&gt;&lt;li&gt;&lt;a href&equals;&quot;http&colon;&sol;&sol;www&period;roberthalf&period;com&sol;technology&sol;it-salary-center&quest;utm&lowbar;source&equals;ZiffD&amp;utm&lowbar;medium&equals;Banner&amp;utm&lowbar;campaign&equals;Havas&lowbar;FY14&lowbar;TechnologyQ4&quot; target&equals;&quot;&lowbar;blank&quot;&gt;2015 Salary Guide&lt;&sol;a&gt;&lt;&sol;li&gt;&lt;li&gt;&lt;a href&equals;&quot;http&colon;&sol;&sol;blog&period;rht&period;com&sol;learn-languages-earn-higher-programmer-salary&sol;&quot; target&equals;&quot;&lowbar;blank&quot;&gt;Earn a Higher Programmer Salary&lt;&sol;a&gt;&lt;&sol;li&gt;&lt;li&gt;&lt;a href&equals;&quot;http&colon;&sol;&sol;blog&period;rht&period;com&sol;high-paying-jobs-tech-creative-pros-2015&sol;&quot; target&equals;&quot;&lowbar;blank&quot;&gt;&lt;img src&equals;&quot;http&colon;&sol;&sol;blog&period;rht&period;com&sol;wp-content&sol;uploads&sol;2015&sol;01&sol;6-Careers2&period;jpg&quot; width&equals;&quot;220&quot; height&equals;&quot;220&quot; alt&equals;&quot;High paying jobs 2015&quot;&gt;&lt;&sol;a&gt;&lt;&sol;li&gt;&lt;&sol;ul&gt;&lt;&sol;fieldset&gt;'), ";
		$sql .= "(6,'','2016 Salary Center','manage_content.php?page=22',1,1,1,'&lt;h2 title&equals;&quot;2016 Salary Center&quot;&gt;2016 Salary Center&lt;&sol;h2&gt;&lt;fieldset&gt;&lt;legend title&equals;&quot;Salary Trends for 2016&quot;&gt;Salary Trends&lt;&sol;legend&gt;&lt;ul&gt;&lt;li&gt;&lt;a href&equals;&quot;https&colon;&sol;&sol;www&period;roberthalf&period;com&sol;technology&sol;2016-salary-center-for-technology-professionals&quot; target&equals;&quot;&lowbar;blank&quot;&gt;2016 Salary Guide&lt;&sol;a&gt;&lt;&sol;li&gt;&lt;&sol;ul&gt;&lt;&sol;fieldset&gt;')";
 
		$this->query($sql);
	}

	private function photosloaddata() {
		$sql  = "INSERT INTO photos ";
		$sql .= "(page_id, position, filename, type, size, caption)";
		$sql .= " VALUES ";
		$sql .= "(1,2,'ts_1000_2.jpg','image/jpeg',36635,'Timex Sinclair with 16K memory expansion'), ";
		$sql .= "(1,3,'ts_1000_3.jpg','image/jpeg',90882,'Timex Sinclair motherboard'), ";
		$sql .= "(3,1,'kaypro_4.jpg','image/jpeg',44307,'Katpro 4 computer'), ";
		$sql .= "(3,2,'kaypro_4_back.jpg','image/jpeg',36472,'Kaypro 4 computer back'), ";
		$sql .= "(1,1,'ts_1000.jpg','image/jpeg',16191,'Still in the original box')";
		$this->query($sql);
		
	}

	private function menuloaddata() {
		$sql  = "INSERT INTO menu ";
		$sql .= "(menu_name, loc, position, level, visible, admin)";
		$sql .= " VALUES ";
		$sql .= "('Content','manage_content.php',1,1,1,1), ";
		$sql .= "('Users','login.php',2,1,1,1), ";
		$sql .= "('School','school.php',3,1,1,1), ";
		$sql .= "('Logout','logout.php',6,2,1,1), ";
		$sql .= "('Menus','manage_menus.php',4,1,1,1), ";
		$sql .= "('Backup','backup.php',5,2,1,1)";
		$this->query($sql);
	}

	
}
$database = new MySQLDatabase();
$db =& $database;

?>
