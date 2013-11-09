// grab_group_info.js <group>
// Returns a JSON object of the groups info.  Script does not log in, you 
//  must do that and pass it through by means of the cookie jar.

var system = require('system');
if (system.args.length === 1) {
    out.error = "Usage: loadspeed.js <some group id>";
    print_exit(1);
}
var address = "http://www.fitbit.com/group/" + system.args[1];

var page = require('webpage').create();
var out = {};

function print_exit(code) {
    console.log(JSON.stringify(out));
    phantom.exit(code);
}

function get_page_title(page) {
    return page.evaluate(
        function () {
            return document.title;
        }
    );
}

function get_page_group_title(page) {
    return page.evaluate(
        function () {
            // Find the groupHeader element
            var elements = document.getElementsByClassName("groupHeader");
            for (var i = 0; i < elements.length; ++i)
            {
                // Make sure we are at the right one
                var titles = elements[i].getElementsByTagName("H1");
                if (titles.length > 0)
                    return titles[0].innerText || titles[0].textContent;
            }

            return '';
        }
    );
}

function get_page_group_description(page) {
    return page.evaluate(
        function () {
            // Find the groupHeader element
            var elements = document.getElementsByClassName("groupHeader");
            for (var i = 0; i < elements.length; ++i)
            {
                // Make sure we are at the right one
                var descriptions = elements[i].getElementsByClassName("description");
                if (descriptions.length > 0)
                    return descriptions[0].innerText || descriptions[0].textContent;
            }

            return '';
        }
    );
}

function get_page_info(page, key) {
    return page.evaluate(
        function (key) {
            // Find the summary element
            var summaries = document.getElementsByClassName("group-summary");
            for (var i = 0; i < summaries.length; ++i)
            {
                // Make sure we are at the right one
                if (summaries[i].tagName == "UL")
                {
                    // Get it children
                    var nodes = summaries[i].childNodes;
                    for (var j = 0; j < nodes.length; ++j)
                    {
                        // Correct one we want?
                        var text = nodes[j].innerText || nodes[j].textContent;
                        if (text.toLowerCase().indexOf(key.toLowerCase()) !== -1)
                        {
                            return text;
                        }
                    }
                }
            }

            return '';
        }, key
    );
}

page.open(address, function (status) {
    if (status !== 'success') {
        out.error = "FAIL to load the address " + address;
        print_exit(1);
    } else {
        out.pagetitle = get_page_title(page);
        out.title = get_page_group_title(page);
        out.description = get_page_group_description(page);
        out.numMembers = get_page_info(page, "members");
        out.numSteps = get_page_info(page, "steps");
        out.numMiles = get_page_info(page, "miles");
        out.numVeryActiveMinutes = get_page_info(page, "very active minutes");
        out.numDaysRemaining = get_page_info(page, "remaining");
    }
    print_exit(0);
});