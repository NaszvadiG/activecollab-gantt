function setScaleConfig(value) {
	switch (value) {
		case "1":
			gantt.config.date_scale = "%d %M";
			gantt.config.scale_unit = "day";
			gantt.config.step = 1;
			gantt.config.min_column_width = 50;
			gantt.config.scale_height = 27;
			gantt.config.subscales = [];
			gantt.templates.date_scale = null;
			gantt.templates.scale_cell_class = function(date) {
				return "gantt_day";
			};
			break;
		case "2":
			gantt.config.scale_unit = "week"; 
			gantt.config.date_scale = "Week #%W";
			gantt.config.min_column_width = 100;
			gantt.config.subscales = [
				{unit:"day", step:7, date:"%d %M" }
			];
			gantt.config.scale_height = 50;
			break;
		case "3":
			gantt.config.scale_unit = "month";
			gantt.config.date_scale = "%M";
			gantt.config.step = 1;
			gantt.config.subscales = [
				{unit:"week", step:1, date:"#%W" }
			];
			gantt.config.scale_height = 50;
			gantt.config.min_column_width = 75;
			gantt.templates.date_scale = null;
			break;
		case "4":
			gantt.config.scale_unit = "month";
			gantt.config.step = 1;
			gantt.config.date_scale = "%M";
			gantt.config.min_column_width = 50;
			gantt.config.scale_height = 50;
			gantt.templates.date_scale = null;
			gantt.config.subscales = [
				{unit:"week", step:1, date:"#%W" }
			];
			break;
		case "5":
			gantt.config.scale_unit = "year";
			gantt.config.step = 1;
			gantt.config.date_scale = "%Y";
			gantt.config.min_column_width = 150;
			gantt.config.scale_height = 50;
			gantt.templates.date_scale = null;
			gantt.config.subscales = [
				{unit:"month", step:1, date:"%M" }
			];
			break;
		case "6":
			gantt.config.scale_unit = "year";
			gantt.config.step = 1;
			gantt.config.date_scale = "%Y";
			gantt.config.min_column_width = 75;
			gantt.config.scale_height = 50;
			gantt.templates.date_scale = null;
			gantt.config.subscales = [
				{unit:"month", step:1, date:"%M" }
			];
			break;
		case "7":
			gantt.config.scale_unit = "year";
			gantt.config.step = 1;
			gantt.config.date_scale = "%Y";
			gantt.config.min_column_width = 50;
			gantt.config.scale_height = 50;
			gantt.templates.date_scale = null;
			gantt.config.subscales = [
				{unit:"month", step:1, date:"%M" }
			];
			break;
		case "8":
			gantt.config.scale_unit = "year";
			gantt.config.step = 1;
			gantt.config.date_scale = "%Y";
			gantt.config.min_column_width = 300;
			gantt.config.scale_height = 25;
			gantt.templates.date_scale = null;
			gantt.templates.scale_cell_class = function(date) {
				return "gantt_year";
			};
			gantt.config.subscales = [];
			break;
	}
}

gantt.templates.task_class  = function(start, end, task) {
    switch (task.id.charAt(0)) {
        case "T":
            return "task";
            break;
        case "M":
            return "milestone";
            break;
    }
};

gantt.config.columns = [
	{ name:"company", label:"Company Name", tree: false, width: 180 },
	{ name:"text", label:"Task name",  tree:true, width:300 },
	{ name:"start_date", label:"Start Date", width: 100, align: "center" },
	{ name:"end_date", label:"End Date", width: 100, align: "center" }
];

gantt.config.fit_tasks = true;
gantt.config.grid_width = 640;
gantt.config.show_progress = true;
gantt.config.autosize = false;
gantt.config.sort = true; 
gantt.config.scale_unit = "week"; 
gantt.config.date_scale = "Week #%W";
gantt.config.min_column_width = 100;
gantt.config.subscales = [
	{unit:"day", step:7, date:"%d %M" }
];
gantt.config.scale_height = 50;