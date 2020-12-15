<div class="footer">Created By <a href="https://davidlomas.eu" title="David Lomas" target="_blank">David Lomas</a> | <a href="https://davidlomas.eu/ict-support-script-free/" title="ICT Support" target="_blank">ICTSupport</a> <?=$version?> GNU GPL</div>
<script type="text/javascript">
// Select the button
const btn = document.querySelector(".btn-toggle");
// Select the theme preference from localStorage
const currentTheme = localStorage.getItem("theme");
// If the current theme in localStorage is "dark"...
if (currentTheme == "dark") {
  // ...then use the .dark-theme class
  	document.body.classList.add("dark-theme");
	document.getElementById("btn-toggle").checked = true;
}
// Listen for a click on the button 
btn.addEventListener("click", function() {
  // Toggle the .dark-theme class on each click
  document.body.classList.toggle("dark-theme");
  
  // Let's say the theme is equal to light
  let theme = "light";
  // If the body contains the .dark-theme class...
  if (document.body.classList.contains("dark-theme")) {
    // ...then let's make the theme dark
    theme = "dark";
  }
	if (document.body.classList.contains("dark-theme")) {
		document.getElementById("btn-toggle").checked = true;
	}
  // Then save the choice in localStorage
  localStorage.setItem("theme", theme);
});
</script>
</body>
</html>