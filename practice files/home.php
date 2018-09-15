<!DOCTYPE html>
<html lang="en">
    
<head>
<title> Penguin </title>
</head>

<nav>
    <p>
    <a href = "page2.php" title = "gopage2">
    Go to page 2!! &nbsp;
    </a>
    <a href = "page1.php" title = "gopage1">
    Go to page 1
    </a>
    </p>
</nav

<body>
    
    <h1>TITLE</h1>
        <div id = "tabContainer">
        <div id = "tabs">
            <ul>
                <li id = "tab1"><a href = "#tabPanel1">Button1
    </a></li>
                <li id = "tab2"><a href = "#tabPanel2">Button2
    </a></li>
                <li id = "tab3"><a href = "#tabPanel3">Button3
    </a></li>
            </ul>
        <div id = "containers">
            <div id = "tabPanel1">
                <h4>Button1</h4>
                <p> I'm coming home, I'm coming home. <br>
                    Tell the world that I'm coming homeeeeeee.</p>
        </div>
        <div id = "tabPanel2">
            <h4>Button2</h4>
            <p>THis is another button</p>
        </div>
        <div id = "tabPanel3">
            <h4>Button3</h4>
            <p>This is the third page lalala</p>
        </div>
    </div>
</div>

    
<script type = "text/javascript">
//tabbed panels

//declare globals to hold all the links and all the panel elements
var tabLinks;
var tabPanels;

window.onload = function() {
    //when the page loads, grab the li elements
    tabLinks =
document.getElementById("tabs").getElementsByTagName("li");
    //Now get all the tab panel container divs
    tabPanels =
document.getElementById("containers").getElementsByTagName("div");
    //activate the _first_ one
    displayPanel(tabLinks[0]);
    //attach event listener to links using onclick and onfocus,
    //fire the displayPanel function, return false to display link
    for (var i = 0; i < tabLinks.length; i++) {
        tabLinks[i].onclick = function() {
            displayPanel(this);
            return false;
        }
    }
}
    
function displayPanel(tabtoActivate) {
    //go through all the <li> slements
    for (var i = 0; i < tabLinks.length; i++) {
        if (tabLinks[i] == tabtoActivate) {
            //if it's the one to activate, change its class
            tabLinks[i].classList.add("active");
            //and display the corresponding panel
            tabPanels[i].style.display = "block";
        } else {
            //remove the active class on the link
            tabLinks[i].classList.remove("active");
            //hide the panel
            tabPanels[i].style.display = "none";
        }
    }
}
</script>

</body>
</html> 