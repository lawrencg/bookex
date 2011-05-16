
var PageName = 'My Book';
var PageId = 'pc8ef9bbb49b44307a94e82ffd662dd4c'
var PageUrl = 'My_Book.html'
document.title = 'My Book';

if (top.location != self.location)
{
	if (parent.HandleMainFrameChanged) {
		parent.HandleMainFrameChanged();
	}
}

var $OnLoadVariable = '';

var $CSUM;

var hasQuery = false;
var query = window.location.hash.substring(1);
if (query.length > 0) hasQuery = true;
var vars = query.split("&");
for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split("=");
    if (pair[0].length > 0) eval("$" + pair[0] + " = decodeURIComponent(pair[1]);");
} 

if (hasQuery && $CSUM != 1) {
alert('Prototype Warning: The variable values were too long to pass to this page.\nIf you are using IE, using Firefox will support more data.');
}

function GetQuerystring() {
    return '#OnLoadVariable=' + encodeURIComponent($OnLoadVariable) + '&CSUM=1';
}

function PopulateVariables(value) {
  value = value.replace(/\[\[OnLoadVariable\]\]/g, $OnLoadVariable);
  value = value.replace(/\[\[PageName\]\]/g, PageName);
  return value;
}

function OnLoad(e) {

}

var u54 = document.getElementById('u54');
gv_vAlignTable['u54'] = 'center';
var u60 = document.getElementById('u60');
gv_vAlignTable['u60'] = 'top';
var u29 = document.getElementById('u29');
gv_vAlignTable['u29'] = 'top';
var u45 = document.getElementById('u45');
gv_vAlignTable['u45'] = 'top';
var u83 = document.getElementById('u83');
gv_vAlignTable['u83'] = 'top';
var u51 = document.getElementById('u51');
gv_vAlignTable['u51'] = 'top';
var u79 = document.getElementById('u79');
gv_vAlignTable['u79'] = 'top';
var u42 = document.getElementById('u42');
gv_vAlignTable['u42'] = 'center';
var u80 = document.getElementById('u80');
gv_vAlignTable['u80'] = 'top';
var u26 = document.getElementById('u26');
gv_vAlignTable['u26'] = 'center';
var u5 = document.getElementById('u5');
gv_vAlignTable['u5'] = 'top';
var u23 = document.getElementById('u23');

var u76 = document.getElementById('u76');
gv_vAlignTable['u76'] = 'center';
var u14 = document.getElementById('u14');
gv_vAlignTable['u14'] = 'center';
var u67 = document.getElementById('u67');

var u20 = document.getElementById('u20');

var u73 = document.getElementById('u73');

var u48 = document.getElementById('u48');
gv_vAlignTable['u48'] = 'top';
var u4 = document.getElementById('u4');
gv_vAlignTable['u4'] = 'top';
var u11 = document.getElementById('u11');

var u64 = document.getElementById('u64');
gv_vAlignTable['u64'] = 'top';
var u70 = document.getElementById('u70');
gv_vAlignTable['u70'] = 'top';
var u39 = document.getElementById('u39');
gv_vAlignTable['u39'] = 'top';
var u55 = document.getElementById('u55');

var u61 = document.getElementById('u61');
gv_vAlignTable['u61'] = 'top';
var u84 = document.getElementById('u84');
gv_vAlignTable['u84'] = 'top';
var u52 = document.getElementById('u52');
gv_vAlignTable['u52'] = 'top';
var u36 = document.getElementById('u36');
gv_vAlignTable['u36'] = 'center';
var u81 = document.getElementById('u81');
gv_vAlignTable['u81'] = 'top';
var u27 = document.getElementById('u27');

u27.style.cursor = 'pointer';
if (bIE) u27.attachEvent("onclick", Clicku27);
else u27.addEventListener("click", Clicku27, true);
function Clicku27(e)
{

if (true) {

	self.location.href="Book_Detail_Intro_Public_Speaking.html" + GetQuerystring();

}

}
gv_vAlignTable['u27'] = 'top';
var u33 = document.getElementById('u33');
gv_vAlignTable['u33'] = 'top';
var u0 = document.getElementById('u0');

var u24 = document.getElementById('u24');
gv_vAlignTable['u24'] = 'center';
var u77 = document.getElementById('u77');
gv_vAlignTable['u77'] = 'top';
var u30 = document.getElementById('u30');
gv_vAlignTable['u30'] = 'top';
var u58 = document.getElementById('u58');
gv_vAlignTable['u58'] = 'top';
var u15 = document.getElementById('u15');

u15.style.cursor = 'pointer';
if (bIE) u15.attachEvent("onclick", Clicku15);
else u15.addEventListener("click", Clicku15, true);
function Clicku15(e)
{

if (true) {

	self.location.href="Dashboard.html" + GetQuerystring();

}

}
gv_vAlignTable['u15'] = 'top';
var u21 = document.getElementById('u21');
gv_vAlignTable['u21'] = 'center';
var u74 = document.getElementById('u74');
gv_vAlignTable['u74'] = 'center';
var u49 = document.getElementById('u49');
gv_vAlignTable['u49'] = 'top';
var u12 = document.getElementById('u12');
gv_vAlignTable['u12'] = 'center';
var u65 = document.getElementById('u65');

u65.style.cursor = 'pointer';
if (bIE) u65.attachEvent("onclick", Clicku65);
else u65.addEventListener("click", Clicku65, true);
function Clicku65(e)
{

if (true) {

	self.location.href="Add_Book.html" + GetQuerystring();

}

}

var u71 = document.getElementById('u71');
gv_vAlignTable['u71'] = 'top';
var u62 = document.getElementById('u62');
gv_vAlignTable['u62'] = 'top';
var u46 = document.getElementById('u46');
gv_vAlignTable['u46'] = 'top';
var u85 = document.getElementById('u85');

u85.style.cursor = 'pointer';
if (bIE) u85.attachEvent("onclick", Clicku85);
else u85.addEventListener("click", Clicku85, true);
function Clicku85(e)
{

if (true) {

	self.location.href="Resources/reload.html#" + encodeURI(PageUrl + GetQuerystring());

}

}
gv_vAlignTable['u85'] = 'top';
var u37 = document.getElementById('u37');
gv_vAlignTable['u37'] = 'top';
var u43 = document.getElementById('u43');

var u17 = document.getElementById('u17');

var u18 = document.getElementById('u18');

var u82 = document.getElementById('u82');
gv_vAlignTable['u82'] = 'top';
var u1 = document.getElementById('u1');
gv_vAlignTable['u1'] = 'center';
var u34 = document.getElementById('u34');
gv_vAlignTable['u34'] = 'top';
var u40 = document.getElementById('u40');
gv_vAlignTable['u40'] = 'top';
var u68 = document.getElementById('u68');
gv_vAlignTable['u68'] = 'center';
var u25 = document.getElementById('u25');

var u31 = document.getElementById('u31');
gv_vAlignTable['u31'] = 'top';
var u59 = document.getElementById('u59');
gv_vAlignTable['u59'] = 'top';
var u22 = document.getElementById('u22');
gv_vAlignTable['u22'] = 'top';
var u75 = document.getElementById('u75');

var u8 = document.getElementById('u8');
gv_vAlignTable['u8'] = 'top';
var u72 = document.getElementById('u72');
gv_vAlignTable['u72'] = 'top';
var u56 = document.getElementById('u56');
gv_vAlignTable['u56'] = 'center';
var u47 = document.getElementById('u47');
gv_vAlignTable['u47'] = 'top';
var u53 = document.getElementById('u53');

var u28 = document.getElementById('u28');
gv_vAlignTable['u28'] = 'top';
var u2 = document.getElementById('u2');

u2.style.cursor = 'pointer';
if (bIE) u2.attachEvent("onclick", Clicku2);
else u2.addEventListener("click", Clicku2, true);
function Clicku2(e)
{

if (true) {

	self.location.href="Logout.html" + GetQuerystring();

}

}
gv_vAlignTable['u2'] = 'top';
var u44 = document.getElementById('u44');
gv_vAlignTable['u44'] = 'center';
var u50 = document.getElementById('u50');
gv_vAlignTable['u50'] = 'top';
var u19 = document.getElementById('u19');

u19.style.cursor = 'pointer';
if (bIE) u19.attachEvent("onclick", u19Click);
else u19.addEventListener("click", u19Click, true);
InsertAfterBegin(document.body, "<DIV class='intcases' id='u19LinksClick'></DIV>")
var u19LinksClick = document.getElementById('u19LinksClick');
function u19Click(e) 
{

	ToggleLinks(e, 'u19LinksClick');
}

InsertBeforeEnd(u19LinksClick, "<div class='intcaselink' onmouseout='SuppressBubble(event)' onclick='u19Clickua635d516fc90407fb38bbf3abc9bfd73(event)'>Book Search</div>");
function u19Clickua635d516fc90407fb38bbf3abc9bfd73(e)
{

	self.location.href="Search_Book_Results.html" + GetQuerystring();

	ToggleLinks(e, 'u19LinksClick');
}

InsertBeforeEnd(u19LinksClick, "<div class='intcaselink' onmouseout='SuppressBubble(event)' onclick='u19Clicku6777edb68a1945899d04972234558e73(event)'>People Search</div>");
function u19Clicku6777edb68a1945899d04972234558e73(e)
{

	self.location.href="Search_People_Results.html" + GetQuerystring();

	ToggleLinks(e, 'u19LinksClick');
}

var u78 = document.getElementById('u78');
gv_vAlignTable['u78'] = 'top';
var u7 = document.getElementById('u7');
gv_vAlignTable['u7'] = 'top';
var u41 = document.getElementById('u41');

var u69 = document.getElementById('u69');
gv_vAlignTable['u69'] = 'top';
var u32 = document.getElementById('u32');
gv_vAlignTable['u32'] = 'top';
var u16 = document.getElementById('u16');
gv_vAlignTable['u16'] = 'top';
var u9 = document.getElementById('u9');

u9.style.cursor = 'pointer';
if (bIE) u9.attachEvent("onclick", Clicku9);
else u9.addEventListener("click", Clicku9, true);
function Clicku9(e)
{

if (true) {

	self.location.href="Resources/reload.html#" + encodeURI(PageUrl + GetQuerystring());

}

}
gv_vAlignTable['u9'] = 'top';
var u13 = document.getElementById('u13');

var u66 = document.getElementById('u66');
gv_vAlignTable['u66'] = 'top';
var u6 = document.getElementById('u6');
gv_vAlignTable['u6'] = 'top';
var u35 = document.getElementById('u35');

var u57 = document.getElementById('u57');
gv_vAlignTable['u57'] = 'top';
var u10 = document.getElementById('u10');

u10.style.cursor = 'pointer';
if (bIE) u10.attachEvent("onclick", Clicku10);
else u10.addEventListener("click", Clicku10, true);
function Clicku10(e)
{

if (true) {

	self.location.href="My_Profile.html" + GetQuerystring();

}

}
gv_vAlignTable['u10'] = 'top';
var u63 = document.getElementById('u63');
gv_vAlignTable['u63'] = 'top';
var u38 = document.getElementById('u38');
gv_vAlignTable['u38'] = 'top';
var u3 = document.getElementById('u3');
gv_vAlignTable['u3'] = 'top';
if (window.OnLoad) OnLoad();
