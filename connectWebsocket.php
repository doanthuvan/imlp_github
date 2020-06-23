<script src="http://socketo.me/vendor/autobahnjs/autobahn/autobahn.js"></script>
<script>
$(function() {
    ab.debug(true,true);
    var conn = new ab.Session('ws://localhost:8082',
        function() {
            conn.subscribe('fork', function(topic, data) {
                let mess = "<p>Your fork is done and this is your new fork repo: <a target='blank' href=" + data + ">URL</a></p>";
                $("#notification").append(mess)
            });
        },
        function() {
            console.warn('WebSocket connection closed');
        },
        {'skipSubprotocolCheck': true}
    );
})
</script>