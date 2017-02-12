<script type="text/javascript">
function toggle_session(session1_text, session2_text) {
	var x = "" + document.getElementById("session_select").value;
	
	if (x == "1") {
		echo_string = "session1_text";
	}
	else {
		echo_string = "session2_text";
	}

    document.getElementById("change_session").innerHTML = echo_string;
}
</script>