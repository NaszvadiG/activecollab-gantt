{title}Gantt{/title}
{add_bread_crumb}All{/add_bread_crumb}

<div class="section_container">
	
	<div class="ganttViewBox">
		<fieldset id="viewLevel">
			<input id="view-project" type="radio" value="project" name="view_level" onchange="triggerFilterChange()" /><label for="view-project">Projects</label>
			<input id="view-project-milestone" type="radio" value="project-milestone" name="view_level" onchange="triggerFilterChange()" /><label for="view-project-milestone">Milestones</label>
			<input id="view-everything" type="radio" value="everything" name="view_level" onchange="triggerFilterChange()" checked="" /><label for="view-everything">Tasks</label>
			
			<a onclick="fetchAndRenderGantt()"><i class="fa fa-tasks padright fa-2x"></i>Render Gantt</a>
		</fieldset>
		<fieldset id="scaleLevel">
			<input id="scale-1" type="radio" value="1" name="scale_level" onchange="scaleChange()" /><label for="scale-1">D</label>
			<input id="scale-2" type="radio" value="2" name="scale_level" onchange="scaleChange()" /><label for="scale-2">W</label>
			<input id="scale-3" type="radio" value="3" name="scale_level" onchange="scaleChange()" checked="" /><label for="scale-3">3M</label>
			<input id="scale-4" type="radio" value="4" name="scale_level" onchange="scaleChange()" /><label for="scale-4">6M</label>
			<input id="scale-5" type="radio" value="5" name="scale_level" onchange="scaleChange()" /><label for="scale-5">9M</label>
			<input id="scale-6" type="radio" value="6" name="scale_level" onchange="scaleChange()" /><label for="scale-6">1Y</label>
			<input id="scale-7" type="radio" value="7" name="scale_level" onchange="scaleChange()" /><label for="scale-7">2Y</label>
			<input id="scale-8" type="radio" value="8" name="scale_level" onchange="scaleChange()" /><label for="scale-8">5Y</label>
		</fieldset>
	</div>
		
	<div class="ganttFilterBox">
		<fieldset id="filterSave">
			<select id="savedFilters">
				<option value="">Select saved filters...</option>
			</select>
			<input type="checkbox" id="isPrivate" name="isPrivate" /><label for="isPrivate"></label>
			<a id="saveCustomFilter" onclick="saveCustomFilter()">Save</a>
			<a id="deleteCustomFilter" onclick="deleteCustomFilter()" class="disabled">Delete</a>
		</fieldset>
		<fieldset id="filterButtons">
			<div id="companies" class="filter_container">
				<a onclick="handleMenu('company')"><i class="fa fa-filter padright"></i>Companies</a>
				<div id="filter">
					<div style="text-align: right; margin-bottom: 7px;"><a style="float: left;" onclick="massCheck('company', 1)">All</a> <a onclick="massCheck('company', 0)">None</a></div>
					<ul id="company_list_dropdown" class="menu">
					</ul>
				</div>
			</div>
			<div id="categories" class="filter_container">
				<a onclick="handleMenu('category')"><i class="fa fa-filter padright"></i>Categories</a>
				<div id="category_filter">
					<div style="text-align: right; margin-bottom: 7px;"><a style="float: left;" onclick="massCheck('category', 1)">All</a><a onclick="massCheck('category', 0)">None</a></div>
					<ul id="category_list_dropdown" class="menu">
						<li><input type="checkbox" id="category-0" value="0" name="show_categories[]" checked />No Category</li>
					</ul>
				</div>
			</div>
			<div id="customField1" class="filter_container">
				<a onclick="handleMenu('c1')"><i class="fa fa-filter padright"></i><span id="customField1Button"></span></a>
				<div id="custom_field_1_filter">
					<div style="text-align: right; margin-bottom: 7px;"><a style="float: left;" onclick="massCheck('custom_field_1', 1)">All</a><a onclick="massCheck('custom_field_1', 0)">None</a></div>
					<ul id="custom_field_1_dropdown" class="menu">
					</ul>
				</div>
			</div>
			<div id="customField2" class="filter_container">
				<a onclick="handleMenu('c2')"><i class="fa fa-filter padright"></i><span id="customField2Button"></span></a>
				<div id="custom_field_2_filter">
					<div style="text-align: right; margin-bottom: 7px;"><a style="float: left;" onclick="massCheck('custom_field_2', 1)">All</a><a onclick="massCheck('custom_field_2', 0)">None</a></div>
					<ul id="custom_field_2_dropdown" class="menu">
					</ul>
				</div>
			</div>
			<div id="customField3" class="filter_container">
				<a onclick="handleMenu('c3')"><i class="fa fa-filter padright"></i><span id="customField3Button"></span></a>
				<div id="custom_field_3_filter">
					<div style="text-align: right; margin-bottom: 7px;"><a style="float: left;" onclick="massCheck('custom_field_3', 1)">All</a><a onclick="massCheck('custom_field_3', 0)">None</a></div>
					<ul id="custom_field_3_dropdown" class="menu">
					</ul>
				</div>
			</div>
		</fieldset>
	</div>
	
	<div id="unseenChanges" class="hideUnseen"><i class="fa fa-warning fa-2x fa-pulse"></i><span>Click "Render Gantt" to realize your changes.</span></div>
	
	<div id="gantt_here" style="width:100%; height:650px;"></div>
	
	<p class="empty_page" style="display:none;">{lang}No projects loaded{/lang}</p>

</div>

<!-- What do we want? FONT-AWESOME! When do we want it? AT THE END! -->
<link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">