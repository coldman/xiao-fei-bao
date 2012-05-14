/**
 * Base functions for manage pages.
 *
 * @Author	RobinHuang
 * @Version	1.0
 */

var tab = null;
var accordion = null;
var tree = null;

function addTab(tabid, text, url)
{
    //tab.addTabItem(tabid: tabid, text: text, url: url);
}

function heightChanged(options)
{
    if (tab) {
	tab.addHeight(options.diff);
    }
    if (accordion && options.middleHeight - 24 > 0) {
	accordion.setHeight(options.middleHeight - 24);
    }
}

$(function(){
    $('#viewport').ligerLayout({
	leftWidth: 200, 
	height: '100%', 
	space: 4, 
	heightDiff: -30, 
	onHeightChanged: heightChanged
    });
    var height = $('.l-layout-center').height();
    $('#navigation').ligerAccordion({height:height - 24, speed:null});
    $('#framecenter').ligerTab({height: height});
});
