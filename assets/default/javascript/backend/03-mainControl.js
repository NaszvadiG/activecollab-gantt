String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

function scaleChange() {
	var to = $('input[name="scale_level"]:checked').val();
	setScaleConfig(to);
	gantt.render();
}

function setScaleChange(scale) {
	var to = $('#scale-' + scale).prop('checked', true);
}

function setViewLevel(viewLevel) {
	var to = $('#view-' + viewLevel).prop('checked', true);
}

function handleMenu(which) {
	switch(which) {
		case 'c1':
			$('#custom_field_1_filter').toggle();
			$('#custom_field_2_filter').hide();
			$('#custom_field_3_filter').hide();
			$('#filter').hide();
			$('#category_filter').hide();
		break;
		
		case 'c2':
			$('#custom_field_2_filter').toggle();
			$('#custom_field_1_filter').hide();
			$('#custom_field_3_filter').hide();
			$('#filter').hide();
			$('#category_filter').hide();
		break;
		
		case 'c3':
			$('#custom_field_3_filter').toggle();
			$('#custom_field_2_filter').hide();
			$('#custom_field_1_filter').hide();
			$('#filter').hide();
			$('#category_filter').hide();
		break;
		
		case 'company':
			$('#filter').toggle();
			$('#custom_field_1_filter').hide();
			$('#custom_field_2_filter').hide();
			$('#custom_field_3_filter').hide();
			$('#category_filter').hide();
		break;
		
		case 'category':
			$('#category_filter').toggle();
			$('#custom_field_1_filter').hide();
			$('#custom_field_2_filter').hide();
			$('#custom_field_3_filter').hide();
			$('#filter').hide();
		break;
	}
}

function getShowCompanies() {
	var show_bycompany = [];
	$("#company_list_dropdown input:checked").each(function() {
		show_bycompany.push(parseInt(this.value));
	});
	
	return show_bycompany;
}

function setShowCompanies(companies) {
	massCheck('company', false);
	$.each(companies, function(k, v) {
		var to = $('#company-' + v).prop('checked', true);
	});
}

function getShowCategories() {
	var show_bycategory = [];
	$("#category_list_dropdown input:checked").each(function() {
		show_bycategory.push(parseInt(this.value));
	});
	
	return show_bycategory;
}

function setShowCategories(categories) {
	massCheck('category', false);
	$.each(categories, function(k, v) {
		var to = $('#category-' + v).prop('checked', true);
	});
}

function getShowCustomField1() {
	var show_bycustomfield_1 = [];
	$("#custom_field_1_dropdown input:checked").each(function() {
		show_bycustomfield_1.push(this.value);
	});
	
	return show_bycustomfield_1;
}

function setShowCustomField1(customField1) {
	if (customField1 !== undefined) {
		massCheck('custom_field_1', false);
		$.each(customField1, function(k, v) {
			var to = $('#custom-1-' + v).prop('checked', true);
		});
	}
}

function getShowCustomField2() {
	var show_bycustomfield_2 = [];
	$("#custom_field_2_dropdown input:checked").each(function() {
		show_bycustomfield_2.push(this.value);
	});
	
	return show_bycustomfield_2;
}

function setShowCustomField2(customField2) {
	if (customField2 !== undefined) {
		massCheck('custom_field_2', false);
		console.log(customField2);
		$.each(customField2, function(k, v) {
			var to = $('#custom-2-' + v).prop('checked', true);
		});
	}
}

function getShowCustomField3() {
	var show_bycustomfield_3 = [];
	$("#custom_field_3_dropdown input:checked").each(function() {
		show_bycustomfield_3.push(this.value);
	});
	
	return show_bycustomfield_3;
}

function setShowCustomField3(customField3) {
	if (customField3 !== undefined) {
		massCheck('custom_field_3', false);
		$.each(customField3, function(k, v) {
			var to = $('#custom-3-' + v).prop('checked', true);
		})
	}
}

function getSavedFilters() {
	$.ajax({
		method: 'GET',
		url: '/public/index.php?path_info=projects/gantt/get_saved_filters',
		data: {},
	}).success(function(result) {
		addSavedFiltersToList(result);
	}).error(function(data, status, headers, config) {
		$.notify(data, "error");
	});
}

function getFilters() {
	$.ajax({
		method: 'GET',
		url: '/public/index.php?path_info=projects/gantt/get_filters',
		data: {},
	}).success(function(result) {
		addCompaniesToList(result.companies);
		addCategoriesToList(result.categories);
		addCustomFieldsToList(result.custom_fields);
	}).error(function(data, status, headers, config) {
		$.notify(data, "error");
	});
}

function addSavedFiltersToList(result) {

	$('#savedFilters').empty();
	$('#savedFilters').append($('<option>', { value: '0' }).text('Select saved filters...'));
	
	$.each(result, function(k, v) {
		$('#savedFilters').append($('<option>', { value : v.id }).text(v.name)); 
	});
	
	$( "#savedFilters" ).change(function() {
		if (document.getElementById('savedFilters').selectedIndex == 0) {
			$('#deleteCustomFilter').addClass('disabled');
		} else {
			$.ajax({
				method: 'GET',
				url: '/public/index.php?path_info=projects/gantt/get_filter_data/' + $('#savedFilters').val(),
			}).success(function(result) {
				setShowCompanies(result.data.companies);
				setShowCategories(result.data.categories);
				setShowCustomField1(result.data.customField1);
				setShowCustomField2(result.data.customField2);
				setShowCustomField3(result.data.customField3);
				setScaleChange(result.data.scaleLevel);
				setViewLevel(result.data.viewLevel);
				document.getElementById('isPrivate').checked = (result.visibility == 'public') ? false : true;
				$('#deleteCustomFilter').removeClass('disabled');
			}).error(function(data, status, headers, config) {
				$.notify(data, "error");
			});
		}
	});
}

function addCompaniesToList(result) {
	var companyList = document.getElementById('company_list_dropdown');
	$.each(result, function(k, v) {
		var listItem = document.createElement('li');
		var labelItem = document.createElement('label');
		labelItem.className = 'selectable';
		var labelString = document.createTextNode(v);
		var inputItem = document.createElement('input');
		inputItem.type = 'checkbox';
		inputItem.value = k;
		inputItem.name = 'show_companies[]';
		inputItem.id = 'company-' + k;
		inputItem.checked = true;
		inputItem.onchange = triggerFilterChange;
		
		labelItem.appendChild(inputItem);
		labelItem.appendChild(labelString);
		listItem.appendChild(labelItem);
		companyList.appendChild(listItem);
	});
	$('#companies').show();
}

function addCategoriesToList(result) {
	var categoryList = document.getElementById('category_list_dropdown');
	$.each(result, function(k, v) {
		var listItem = document.createElement('li');
		var labelItem = document.createElement('label');
		labelItem.className = 'selectable';
		var labelString = document.createTextNode(v);
		var inputItem = document.createElement('input');
		inputItem.type = 'checkbox';
		inputItem.value = k;
		inputItem.name = 'show_categories[]';
		inputItem.id = 'category-' + k;
		inputItem.checked = true;
		inputItem.onchange = triggerFilterChange;
		
		labelItem.appendChild(inputItem);
		labelItem.appendChild(labelString);
		listItem.appendChild(labelItem);
		categoryList.appendChild(listItem);
	});
	$('#categories').show();
}

function addCustomFieldsToList(result) {
	var customField1 = document.getElementById('custom_field_1_dropdown');
	document.getElementById('customField1Button').innerHTML = result.field_1.name;

	if ('field_1' in result) {
		$.each(result.field_1.values, function(k, v) {
			v = (v == null) ? 'None' : v;
			var listItem = document.createElement('li');
			var labelItem = document.createElement('label');
			labelItem.className = 'selectable';
			var labelString = document.createTextNode(v);
			var inputItem = document.createElement('input');
			inputItem.type = 'checkbox';
			inputItem.value = v.replace(/[^A-Z0-9]/ig, "_");
			inputItem.name = 'show_custom_field_1[]';
			inputItem.id = 'custom-1-' + v.replace(/[^A-Z0-9]/ig, "_");
			inputItem.checked = true;
			inputItem.onchange = triggerFilterChange;
			
			labelItem.appendChild(inputItem);
			labelItem.appendChild(labelString);
			listItem.appendChild(labelItem);
			customField1.appendChild(listItem);
		});
		$('#customField1').show();
	}
	
	if ('field_2' in result) {
		var customField2 = document.getElementById('custom_field_2_dropdown');
		document.getElementById('customField2Button').innerHTML = result.field_2.name;
		$.each(result.field_2.values, function(k, v) {
			v = (v == null) ? 'None' : v;
			var listItem = document.createElement('li');
			var labelItem = document.createElement('label');
			labelItem.className = 'selectable';
			var labelString = document.createTextNode(v);
			var inputItem = document.createElement('input');
			inputItem.type = 'checkbox';
			inputItem.value = v.replace(/[^A-Z0-9]/ig, "_");
			inputItem.name = 'show_custom_field_2[]';
			inputItem.id = 'custom-2-' + v.replace(/[^A-Z0-9]/ig, "_");
			inputItem.checked = true;
			inputItem.onchange = triggerFilterChange;
			
			labelItem.appendChild(inputItem);
			labelItem.appendChild(labelString);
			listItem.appendChild(labelItem);
			customField2.appendChild(listItem);
		});
		$('#customField2').show();
	}
	
	if ('field_3' in result) {
		var customField3 = document.getElementById('custom_field_3_dropdown');
		document.getElementById('customField3Button').innerHTML = result.field_3.name;
		$.each(result.field_3.values, function(k, v) {
			v = (v == null) ? 'None' : v;
			var listItem = document.createElement('li');
			var labelItem = document.createElement('label');
			labelItem.className = 'selectable';
			var labelString = document.createTextNode(v);
			var inputItem = document.createElement('input');
			inputItem.type = 'checkbox';
			inputItem.value = v.replace(/[^A-Z0-9]/ig, "_");
			inputItem.name = 'show_custom_field_3[]';
			inputItem.id = 'custom-3-' + v.replace(/[^A-Z0-9]/ig, "_");
			inputItem.checked = true;
			inputItem.onchange = triggerFilterChange;
			
			labelItem.appendChild(inputItem);
			labelItem.appendChild(labelString);
			listItem.appendChild(labelItem);
			customField3.appendChild(listItem);
		});
		$('#customField3').show();
	}
}

function saveCustomFilter() {
	var companies = getShowCompanies();
	var categories = getShowCategories();
	var customField1 = getShowCustomField1();
	var customField2 = getShowCustomField2();
	var customField3 = getShowCustomField3();
	var getViewLevel = $('input[name="view_level"]:checked').val();
	var getScaleLevel = $('input[name="scale_level"]:checked').val();
	
	var jsonObject = {
		'companies': companies,
		'categories': categories,
		'customField1': customField1,
		'customField2': customField2,
		'customField3': customField3,
		'viewLevel': getViewLevel,
		'scaleLevel': getScaleLevel
	}
	
	var visibility = (document.getElementById('isPrivate').checked) ? 'private' : 'public';
	
	var selected = document.getElementById('savedFilters');
	if (selected.selectedIndex > 0) {
		var isOk = confirm('You are overwriting an existing filter, are you sure?');
		if (!isOk) {
			return;
		}
		var name = selected.options[selected.selectedIndex].text;
	} else {
		var name = prompt('Please name your new saved filter:', 'My Saved Filter');
		if (name == '') {
			return;
		}
	}
	
	$.ajax({
		method: 'POST',
		url: '/public/index.php?path_info=projects/gantt/save_filter/' + $('#savedFilters').val(),
		data: {
			'name': name,
			'data': jsonObject,
			'visibility': visibility 
		},
	}).success(function(result) {
		$.notify(result.message, result.status);
		getSavedFilters();
	}).error(function(data, status, headers, config) {
		$.notify(data, "error");
	});
}

function deleteCustomFilter() {
	if (document.getElementById('savedFilters').selectedIndex == 0) {
		return;
	}
	
	var isOk = confirm('Are you sure you want to delete this filter?');
	if (!isOk) {
		return;
	}
	
	$.ajax({
		method: 'GET',
		url: '/public/index.php?path_info=projects/gantt/delete_filter/' + $('#savedFilters').val(),
		data: {},
	}).success(function(result) {
		$.notify(result.message, result.status);
		$('#deleteCustomFilter').addClass('disabled');
		getSavedFilters();
	}).error(function(data, status, headers, config) {
		$.notify(data, "error");
	});
}

function massCheck(which, checked) {
	switch(which) {
		case 'company':
			$("#company_list_dropdown input").prop('checked', checked);
		break;
		case 'category':
			$("#category_list_dropdown input").prop('checked', checked);
		break;
		case 'custom_field_1':
			$("#custom_field_1_dropdown input").prop('checked', checked);
		break;
		case 'custom_field_2':
			$("#custom_field_2_dropdown input").prop('checked', checked);
		break;
		case 'custom_field_3':
			$("#custom_field_3_dropdown input").prop('checked', checked);
		break;
	}
	
	triggerFilterChange();
}

function startGantt() {
	$('#gantt_here').show();
	gantt.init("gantt_here");
	$('#gantt_here').hide();
}

function clearGantt() {
	gantt.clearAll();
	$('#gantt_here').hide();
}

function fetchAndRenderGantt() {
	gantt.clearAll();
	satisfyFilterChange();
	
	$('#renderGantt').show();
	$('#gantt_here').show();
	
	$.ajax({
		method: 'POST',
		url: '/public/index.php?path_info=projects/gantt/get_projects',
		data: {
			'companies[]': getShowCompanies(),
			'categories[]': getShowCategories(),
			'custom_field_1[]': getShowCustomField1(),
			'custom_field_2[]': getShowCustomField2(),
			'custom_field_3[]': getShowCustomField3(),
			'view_level': $('input[name="view_level"]:checked').val(),
		},
	}).success(function(result) {
		gantt.parse(result);
	}).error(function(data, status, headers, config) {
		console.log(data);
	});
}

var hasFiltersChanged = false;
function triggerFilterChange() {
	hasFiltersChanged = true;
	$('#unseenChanges').removeClass('hideUnseen');
	$('#unseenChanges').addClass('showUnseen');
}
function satisfyFilterChange() {
	hasFiltersChanged = false;
	$('#unseenChanges').removeClass('showUnseen');
	$('#unseenChanges').addClass('hideUnseen');
}

/**
 * Hack using polling since activeCollab likes to be difficult.
 * and loads in the page either via navigating to it, or JSON. 
 * Depends on its mood.
 */
var current_href = '';
var waitForGanttInterval;
$(document).ready(function() {
	setInterval(function(){
		if (window.location.href != current_href) {
			current_href = window.location.href;
			if (window.location.pathname == '/projects/gantt' || window.location.pathname == '/projects/gantt/') {
				waitForGanttInterval = setInterval(function() {
					if ($('#gantt_here').length > 0) {
						$.when(getFilters()).done(function() {
							$.when(getSavedFilters()).done(function() {
								startGantt();
							});
						});	
						clearInterval(waitForGanttInterval);
					}
				}, 50);	
			}
		}
	}, 50);
	
});