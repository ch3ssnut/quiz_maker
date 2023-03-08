
        function add() {
            var list = document.getElementById('anwser-fields-list');
            // console.log(list.dataset.data-widget-counter);
            // Try to find the counter of the list or use the length of the list
            var counter = list.dataset.widgetCounter;
            // || list.children().length;
            
            if(counter > 5) {
                alert("Too many anwsers")
                return
            }
            
            // grab the prototype template
            var newWidget = list.dataset.prototype;
            // replace the "__name__" used in the id and name of the prototype
            // with a number that's unique to your emails
            // end name attribute looks like name="contact[emails][2]"
            newWidget = newWidget.replace(/__name__/g, counter);
            // Increase the counter
            counter++;
            
            // And store it, the length cannot be used if deleting widgets is allowed
            list.dataset.widgetCounter = counter;
            
            // create a new list element and add it to the list
            // var newElem = jQuery(list.attr('data-widget-tags')).html(newWidget);
            const newElem = document.createElement("div");
            newElem.innerHTML = newWidget;

            // console.log(newElem);
            // newElem.appendTo(list);
            list.append(newElem);
        }
        
        // jQuery(document).ready(function () {

        //     jQuery('.add-another-collection-widget').click(function (e) {
        //         add();
        //     });
        // });
        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById("add-another-collection-widget").onclick = () => {
                add();
            }
        });