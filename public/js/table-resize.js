$(function() {
  var headers = $("table.table th");
  
    var container,
    var numberOfCol,
    var sibTotalWidth;
  
  $(headers).resizable({
       handles: 'e',
       start: function(event, ui){
            sibTotalWidth = ui.originalSize.width + ui.originalElement.next().outerWidth();
            container = 
        },
        stop: function(event, ui){     
            var cellPercentWidth=100 * ui.originalElement.outerWidth() / container.innerWidth();
            ui.originalElement.css('width', cellPercentWidth + '%');  
            var nextCell = ui.originalElement.next();
            var nextPercentWidth=100 * nextCell.outerWidth()/container.innerWidth();
            nextCell.css('width', nextPercentWidth + '%');
        }, 
        resize: function(event, ui){ 
            ui.originalElement.next().width(sibTotalWidth - ui.size.width); 
        }
  });
});