<?php require_once '../includes/initialize.php'; ?>
<?php
$menus = Menu::find_all_order_by_m_order();
$pages = Page::find_by_subject_id(Subject::find_by_menu_name("Index")->id);
?>
<?php include_layout_template('header.php'); ?>
<!-- 		<div id="menu"> -->
<!-- 			<nav title="Personal Site Menu"> -->
				<?php //foreach($menus as $menu){ ?>
					<?php //if ($menu->level == 2) { ?>
<!-- 						<br/> -->
					<?php //} ?>
					<a href="<?php //echo ADMIN_PATH.DS.$menu->loc; ?>"><?php //echo $menu->menu_name; ?></a>
				<?php //} ?>
<!-- 			</nav> -->
<!-- 		</div> -->
		<div id="mainbody">
			<header><h1 title="My Resume"><script type="text/javascript">/*full*/var genheading="242f762qC7ZLWCM74rW3504NqZq892CVG74Pg3J36247u693Fe4F7aQPdip360626W6GPbzj43b8aKC36849W8BX7ZPt7Y26qEH3582W3b67ce8FW3sv9Pp36484s34A9ZTno6HDF8x3192cLgw8nw29Hn632jE3462pLJ76G34gab97CpW3276W3b67ce8FW3sv9Pp319268PRM87WLZmGd82f3444D2bhQ4oQR9r696mg3606D2bhQ4oQR9r696mg3690W3b67ce8FW3sv9Pp3690zUL38p8dbt39nX3K36669W8BX7ZPt7Y26qEH3672";document.write(getPass(genheading));</script></h1>
			<span id="smalltxt" class="cell"><script type="text/javascript">var mai1="228f762qC7ZLWCM74rW3296W3b67ce8FW3sv9Pp34049R7Y8s3yRZx47LJe34609v2ib2s6f38RNsUq34609642wzn2DPVQ2huy3380";var mai2="25626W6GPbzj43b8aKC3576D2bhQ4oQR9r696mg3888cLgw8nw29Hn632jE3912xd4a783kVB4bA3Md3928D2bhQ4oQR9r696mg3760";var mai3="2637u693Fe4F7aQPdip3630cLgw8nw29Hn632jE387373vi689xjN9PvfAR41026876WvUNpk8Y8TF4V3981cLgw8nw29Hn632jE41035";var hst="2429W8BX7ZPt7Y26qEH3654NqZq892CVG74Pg3J35829642wzn2DPVQ2huy36307u693Fe4F7aQPdip3648D2bhQ4oQR9r696mg32767u693Fe4F7aQPdip3594876WvUNpk8Y8TF4V36669642wzn2DPVQ2huy3654";document.write('<a href="mailto: '+getPass(mai1)+getPass(mai2)+getPass(mai3)+'@'+getPass(hst)+'" style="color:black;text-decoration:none;background-color:transparent;" class="cell" title="My Email">'+getPass(mai1)+getPass(mai2)+getPass(mai3)+'@'+getPass(hst)+'</a>');</script></span></header>
			
			<h2 title="About me">About me</h2>
			
			
			
			<fieldset><!-- style="float:right; position:absolute; top:170px;right:15px; width:170px; height=94px;" -->
				<legend title="A brief history">A brief history of <span class="txtSizeBold"><script type="text/javascript">/*First and last*/var genname="2429v2ib2s6f38RNsUq350473vi689xjN9PvfAR36249642wzn2DPVQ2huy3606u4aD3h7X49wYB7JK36849642wzn2DPVQ2huy3582pLJ76G34gab97CpW3648D2bhQ4oQR9r696mg3192f762qC7ZLWCM74rW344473vi689xjN9PvfAR36067u693Fe4F7aQPdip36907u693Fe4F7aQPdip3690f762qC7ZLWCM74rW366668PRM87WLZmGd82f3672";document.write('&nbsp;'+getPass(genname));</script></span></legend>
<!-- 				<div class="mainimg"  ><img style="width:100px; height:100px;" src="media/mypic_wolf_resting.jpg"  alt="My picture" title="My picture" /></div> -->
					<?php foreach ($pages as $page) { ?>
						<?php echo html_entity_decode($page->content, ENT_QUOTES); ?>
					<?php } ?>
						
	
<!-- 					<p>In 1982 I bought my first computer, a <a href="timex.html" title="My first computer">Timex/Sinclair 1000</a> with 2K memory and I also bought the 16K memory expansion pack. This computer used the Basic programming language and worked on a regular TV.<br>It also had the capability to save the programs to a standard cassette recorder.</p> -->

<!-- 					<p>I graduated from the 8th grade, which was the highest grade level at our school. I was given the choice to go to a private high school or go to work. I went to work with my dad and worked several miscellaneous jobs until 1985 when our community received the approval from the state educational board to establish the public high school.  It was phased in over a 4 year period and I went back to school as a high school freshman when I was 21.  One year after that I was offered a job by my dad to work in <a href="http://callvillebay.com/" target="_blank" title="I worked here for 2 years">Callville Bay</a> on Lake Mead.  We worked there for one year and then I returned home to go to work for the school as the Audio/Visual Technician Assistant.  It was there that I began programming in dBase II using a <a href="kaypro_4.html" title="I used this for 1 year">Kaypro 4</a> then dBase III. That was a good job for several years until the budget began to shrink and I was laid off.</p> -->

<!-- 					<p>My best friend, who owned a computer shop, introduced me to <span title="I learned programming using Foxpro&trade; up to version 2.6">Foxpro</span>&trade; and I began learning programming using <span title="I learned programming using Foxpro&trade; up to version 2.6">Foxpro</span>&trade;. <span title="I learned programming using Foxpro&trade; up to version 2.6">Foxpro</span>&trade; would run circles around the dBase platform. We worked on several projects together and I also began working on projects on my own.  I wrote a program for the local private university to keep track of students, instructors, classes and schedules.  I wrote my own password routine that would encrypt the password with the ability to recover in the event that the password was lost or forgotten.</p> -->

<!-- 					<p>In early '99 I moved to Littlefield, AZ and began working for a internet company out of Mesquite, Nevada.  I also started smoking. In October 2000 I left Mesquite, Nevada and moved to Indiana and in November started working for Swifty Gas.  I worked there receiving several promotions and in 2002 I was promoted to manager and worked at several of their stations. In October 2004 I left Swifty Gas.</p> -->

<!-- 					<p>I went to work for Porter Engineered Systems through a temporary service because I didn't have my GED.  In December of 2004 I finished my GED and received my GED diploma in January of 2005 and I began my employment with Porter Engineered Systems.  In January 2008 I took a very serious look at my life and quit smoking and found myself searching for the truth through fasting and prayer.  In 2010 I began attending church and in 2012 I became a member of <a href="http://mormon.org/" target="_blank" style="text-decoration:none; color:#336633;" title="I became a member in 2012"><!-- I left the text decoration off on purpose --><!-- The Church of Jesus Christ of Latter-day Saints</a>. In the meantime, in 2010 I enrolled at <a href="http://ivytech.edu/" target="_blank" title="I have 2 degrees from Ivy Tech">Ivy Tech Community College</a> and graduated with an AS in Agriculture in 2012.  I re-enrolled again and in Fall of 2013 I graduated with AAS in Accounting.</p> -->

				</fieldset>
<!-- 				<p>In the Spring of 2014 I enrolled at <a href="http://iuk.edu/" target="_blank">Indiana University Kokomo</a>. -->
<!-- 					<br /><br /> -->
<!-- 					I have a graduation date for May 2016!<br/> -->
<!-- 				</p> -->


			</div>
<?php include_layout_template('footer.php') ?>

