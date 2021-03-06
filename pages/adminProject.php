<?php
include_once '../bootstrap.php';

use DataAccess\CapstoneProjectsDao;
use DataAccess\KeywordsDao;

session_start();

include_once PUBLIC_FILES . '/lib/shared/authorize.php';

$isAdmin = isset($_SESSION['userID']) && !empty($_SESSION['userID']) 
	&& isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] == 'Admin';

$userId = $_SESSION['userID'];

allowIf($isAdmin);

$projectsDao = new CapstoneProjectsDao($dbConn, $logger);
$keywordsDao = new KeywordsDao($dbConn, $logger);

include_once PUBLIC_FILES . '/modules/cards.php';

$title = 'Admin Project Control';
$css = array(
    'assets/css/sb-admin.css'
);
include_once PUBLIC_FILES . '/modules/header.php';

?>
<br/>
<div style="background-color:silver">

	<div id="wrapper">
	<!-- Sidebar -->
	<ul class="sidebar navbar-nav">
		<li class="nav-item">
			<a class="nav-link" href="pages/adminInterface.php">
				<i class="fas fa-fw fa-tachometer-alt"></i>
				<span>Dashboard</span>
			</a>
		</li>

		<!-- PAGES FOLDER DROP DOWN ON SIDE BAR
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-fw fa-folder"></i>
				<span>Pages</span>
			</a>
			<div class="dropdown-menu" aria-labelledby="pagesDropdown">
				<h6 class="dropdown-header">Login Screens:</h6>
				<a class="dropdown-item" href="login.html">Login</a>
				<a class="dropdown-item" href="register.html">Register</a>
				<a class="dropdown-item" href="forgot-password.html">Forgot Password</a>
				<div class="dropdown-divider"></div>
				<h6 class="dropdown-header">Other Pages:</h6>
				<a class="dropdown-item" href="404.html">404 Page</a>
				<a class="dropdown-item" href="blank.html">Blank Page</a>
			</div>
		</li>
                     -->

		<li class="nav-item active">
			<a class="nav-link" href="pages/adminProject.php">
				<i class="fas fa-fw fa-chart-area"></i>
				<span>Projects</span></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="pages/adminUser.php">
				<i class="fas fa-fw fa-table"></i>
				<span>Users</span></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="pages/adminApplication.php">
				<i class="fas fa-fw fa-file-invoice"></i>
				<span>Applications</span></a>
		</li>
	</ul>
	<div class="container-fluid">
		<br>
			<!-- Breadcrumbs-->
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a>Projects</a>
				</li>
				<li class="breadcrumb-item active">Approval Proccess</li>
			</ol>

		<!--
					<div class="col-sm-3">
						<div class="form-group">
							<label for="projectShowSelect">Show..</label>
							<select class="form-control" id="projectShowSelect" onchange="showAdminNeeded()">
								<option></option>
								<option>Admin Required</option>
								<option>Approved Projects</option>
								<option>Not Yet Submitted</option>
								<option>Archived</option>

							</select>
						</div>
					</div>

					

							
-->

		<h1>Admin Project Approval</h1>
		<div class="row">
		<div class="col-sm-3">
            <h2>Search and Filter</h2>
            <div class="row">
                <div class="col-sm-12">
                    <input class="form-control" id="filterInput" type="text" placeholder="Search..." />
                    <br />

<!-- CHECKBOX HIDE IF PROJECTS REQUIRE NDA NOT FUNCTIONING
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="NDAFilterCheckBox" />
                        <label for="NDAFilterCheckBox">Hide projects that require an NDA/IP</label>
                    </div>
-->
                </div>


                <div class="col-sm-12">
				
						<div class="form-check">
							<input type="checkbox" class="form-check-input" id="ApprovalRequiredCheckBox">
							<label for="ApprovalRequiredCheckBox">Hide projects do NOT need Admin Action</label>
						</div>

						<div class="form-check">
							<input type="checkbox" class="form-check-input" id="archivedCheckBox">
							<label for="archivedCheckBox">Hide Archived projects</label>
						</div>

						<div class="form-check">
							<input type="checkbox" class="form-check-input" id="notSubmittedCheckBox">
							<label for="notSubmittedCheckBox">Hide Not-Submitted projects</label>
						</div>

						<div class="form-check">
							<input type="checkbox" class="form-check-input" id="NDAFilterCheckBox">
							<label for="NDAFilterCheckBox">Hide projects that require an NDA/IP</label>
						</div>
				
                    
                    Sort By...
                    <div class="custom-control custom-radio">
                        <input
                            type="radio"
                            id="sortTitleAscRadio"
                            value="sortTitleAsc"
                            name="sortRadio"
                            class="custom-control-input"
                        />
                        <label class="custom-control-label" for="sortTitleAscRadio">Title (A..Z)</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input
                            type="radio"
                            id="sortTitleDescRadio"
                            value="sortTitleDesc"
                            name="sortRadio"
                            class="custom-control-input"
                        />
                        <label class="custom-control-label" for="sortTitleDescRadio">Title (Z..A)</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input
                            type="radio"
                            id="sortDateDescRadio"
                            value="sortDateDesc"
                            name="sortRadio"
                            class="custom-control-input"
                        />
                        <label class="custom-control-label" for="sortDateDescRadio">Date (Recent)</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input
                            type="radio"
                            id="sortDateAscRadio"
                            value="sortDateAsc"
                            name="sortRadio"
                            class="custom-control-input"
                        />
                        <label class="custom-control-label" for="sortDateAscRadio">Date (Oldest)</label>
                    </div>
                    
				</div>
            </div>
        </div>

        <div class="col-sm-9 scroll jumbotron capstoneJumbotron">
            <div class="masonry" id="projectCardGroup">
                <?php
					$projects = $projectsDao->getCapstoneProjectsForAdmin();
					renderAdminProjectCardGroup($projects, $keywordsDao, false);
					?>
            </div>
       </div> 
    </div>
</div>
<script type="text/javascript">

    /*********************************************************************************
    * Function Name: strstr()
    * Description: Mimics strstr() php function that searches for the first occurence
    * of a string (needle) in another string (haystack).
    *********************************************************************************/
    function strstr(haystack, needle, bool) {
        var pos = 0;
        haystack += '';
        pos = haystack.toLowerCase().indexOf((needle + '').toLowerCase());
        if (pos == -1) {
            return false;
        } else {
            if (bool) {
                return haystack.substr(0, pos);
            } else {
                return haystack.slice(pos);
            }
        }
    }

    $(document).ready(function(){

      //As each letter is typed in filterInput, filtering of cards will occur.
      //For drop down lists, like filtering by key word, filterInput is programmically
      //filled and keydown behavior is explicitly called.
      $("#filterInput").keydown(function(){
    	var value = $(this).val().toLowerCase();

    	for(var i = 0; i < <?php echo $numCardsCreated; ?>; i++){
    		if($("#projectCard" + i).text().toLowerCase().indexOf(value) > -1){
    			$("#projectCard" + i).show();
    		}
    		else{
    			$("#projectCard" + i).hide();
    		}
    	}
      });

	  $("#ApprovalRequiredCheckBox").change(function(){
	 if($(this).is(":checked")){
		for(var i = 0; i < <?php echo $numCardsCreated; ?>; i++){
			if(($("#projectCard" + i).text().toLowerCase().indexOf("category placement") <= -1) && ($("#projectCard" + i).text().toLowerCase().indexOf("pending approval") <= -1)) {
				$("#projectCard" + i).hide();
			}
		}

	}
	 else{
		for(var i = 0; i < <?php echo $numCardsCreated; ?>; i++){
			$("#projectCard" + i).show();
		}
	 }
	});

	
	$("#archivedCheckBox").change(function(){
	 if($(this).is(":checked")){
		for(var i = 0; i < <?php echo $numCardsCreated; ?>; i++){
			if(($("#projectCard" + i).text().toLowerCase().indexOf("archived") > -1)) {
				$("#projectCard" + i).hide();
			}
		}
	 }
	 else{
		for(var i = 0; i < <?php echo $numCardsCreated; ?>; i++){
			$("#projectCard" + i).show();
		}
	 }
	});

	$("#notSubmittedCheckBox").change(function(){
	 if($(this).is(":checked")){
		for(var i = 0; i < <?php echo $numCardsCreated; ?>; i++){
			if($("#projectCard" + i).text().toLowerCase().indexOf("not yet submitted") > -1)  {
				$("#projectCard" + i).hide();
			}
		}
	 }
	 else{
		for(var i = 0; i < <?php echo $numCardsCreated; ?>; i++){
			$("#projectCard" + i).show();
		}
	 }
	});


      //Fixme: Future Implementation, allow checkbox to be checked and user to
      //still filter additional options.
      $("#NDAFilterCheckBox").change(function(){
    	 if($(this).is(":checked")){
    		for(var i = 0; i < <?php echo $numCardsCreated; ?>; i++){
    			//-1 is returned by indexOf(String) if the String parameter passed in
    			//does not exist anywhere within the text. Otherwise, its index would
    			//be returned.
    			if($("#projectCard" + i).text().toLowerCase().indexOf("nda required") > -1 || $("#projectCard" + i).text().toLowerCase().indexOf("nda/ip required") > -1) {
    				$("#projectCard" + i).hide();
    			}
    		}
    	 }
    	 else{
    		for(var i = 0; i < <?php echo $numCardsCreated; ?>; i++){
    			$("#projectCard" + i).show();
    		}
    	 }
      });

      //Performs sorting functionality based on which radio button is chosen.
    	$('input[name="sortRadio"]').change(function() {
    		switch ($(this).val()) {
    			case "sortTitleAsc":
    				var mylist = $('#projectCardGroup');
    				var listitems = mylist.children('div').get();
    				listitems.sort(function(a, b) {
    				   return $(a).text().toUpperCase().localeCompare($(b).text().toUpperCase());
    				});

    				$.each(listitems, function(index, item) {
    				   mylist.append(item);
    				});
    				break;
    			case "sortTitleDesc":
    				var mylist = $('#projectCardGroup');
    				var listitems = mylist.children('div').get();
    				listitems.sort(function(a, b) {
    				   return $(b).text().toUpperCase().localeCompare($(a).text().toUpperCase());
    				});

    				$.each(listitems, function(index, item) {
    				   mylist.append(item);
    				});
    				break;
    			case "sortDateAsc":
    				var mylist = $('#projectCardGroup');
    				var listitems = mylist.children('div').get();
    				listitems.sort(function(a, b) {
    				   return strstr($(a).text(), "Last Updated:").toUpperCase().localeCompare(strstr($(b).text(), "Last Updated:").toUpperCase());
    				});

    				$.each(listitems, function(index, item) {
    				   mylist.append(item);
    				});
    				break;
    			case "sortDateDesc":
    				var mylist = $('#projectCardGroup');
    				var listitems = mylist.children('div').get();
    				listitems.sort(function(a, b) {
    				   return strstr($(b).text(), "Last Updated:").toUpperCase().localeCompare(strstr($(a).text(), "Last Updated:").toUpperCase());
    				});

    				$.each(listitems, function(index, item) {
    				   mylist.append(item);
    				});
    				break;
    		};
    	});

		
		// Automatically check the Hide Projects that do NOT need Admin Approval Checkbox and trigger ajax
		$('#ApprovalRequiredCheckBox').prop('checked', true).change();
		$("#sortTitleAscRadio").prop("checked", true);

    });

    function filterSelectChanged(filterObject){
    	var value = filterObject.value;
    	$("#filterInput").val(value);

    	//Manually trigger keydown to mimic keydown function feature.
    	//Attempted to programmically toggleProjectCard, but ran into
    	//logical bug 2/26/19.
        var e = jQuery.Event("keydown");
        e.which = 77;
        $("#filterInput").trigger(e);
	}
	
	function showAdminNeeded() {
		$.ajax({
                type: 'POST',
                url: './modules/filter.php',
                dataType: 'html',
                data: {action: 'adminRequired'},
                success: function(result)
                    {
                        $('#projectCardGroup').load(result);  
                    },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(xhr.responseText);
                    alert(thrownError);
                }
            });
		
	}
</script>


<?php 
include_once PUBLIC_FILES . '/modules/footer.php'; 
?>

