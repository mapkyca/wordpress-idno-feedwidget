
/**
 * Render the idno feed widget.
 * @param {type} data
 * @returns {undefined}
 */
function idno_feedwidget(data) {

    var cnt = 0;
    data.items.forEach(function(entry) {


        if (cnt < count) { // Do it this way until idno upstream takes a limit param
            
            var item = document.createElement('li');
            var title;
            
            item.class = 'idno-entry idno-entry-' + entry.objectType;
            if (entry.object.objectType == 'image') {
                var attachments = "";
                entry.object.attachments.forEach(function (attachment){
		    if ((typeof entry.object.thumbnails != 'undefined') && (typeof entry.object.thumbnails.small != 'undefined')) {
			attachments += "<a class=\"image\" rel=\"lightbox\" href=\"" + attachment.url +"\"><img style=\"width:100px;\" src=\"" + entry.object.thumbnails.small + "\" /></a>";
		    }
		    else
			attachments += "<a class=\"image\" rel=\"lightbox\" href=\"" + attachment.url +"\"><img style=\"width:100px;\" src=\"" + attachment.url + "\" /></a>";
                });
                item.innerHTML = "<a title=\"" + entry.actor.displayName + "\" href=\"" + entry.actor.url + "\" target=\"_blank\"><img style=\"float: left; margin-right: 5px; width:25px;\" alt=\"" + entry.actor.displayName + "\" src=\"" + entry.actor.image.url + "\" /></a>" +
                        "<a title=\"" + entry.published + "\" href=\"" + entry.object.url + "\" target=\"_blank\">" + entry.object.displayName + "</a>" + "<div style=\"text-align: center;\" class=\"attachments\">" + attachments + "</div>";
            }
            else {
                title = entry.object.content;
                if (title.length > 50)
                    title = title.substr(0,50) + '...';
                item.innerHTML = "<a title=\"" + entry.actor.displayName + "\" href=\"" + entry.actor.url + "\" target=\"_blank\"><img style=\"float: left; margin-right: 5px; width:25px;\" alt=\"" + entry.actor.displayName + "\" src=\"" + entry.actor.image.url + "\" /></a>" +
                    "<a title=\"" + entry.published + "\" href=\"" + entry.object.url + "\" target=\"_blank\">" + title + "</a>";
            }

            document.getElementById(widget_id + '-content').appendChild(item);
            cnt++;
        }
    });
}