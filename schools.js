YUI().use('node', 'io', 'dump', 'json-parse', 'io-xdr', function(Y){
	Y.one('tr button').on('click', function(e){
		console.log(this.ancestor().siblings("td.name")._nodes[0].innerHTML);
		var data = '"school":{"id":"0","ltiConsumerPassword":"'+this.ancestor().siblings("td.ltiPass")._nodes[0].innerHTML+'","name":"'+this.ancestor().siblings("td.name")._nodes[0].innerHTML+'","schoolCode":"'+ this.ancestor().siblings("td.schoolCode")._nodes[0].innerHTML+'"}';
		console.log(Y.JSON.parse(data));
		var config = {
			method: 'POST',
			data: Y.JSON.parse(data),
			headers: {
				'Content-Type': 'application/json',
			},
			on: {
				success: function(id, response, args){
					console.log("Yey!");
				},
				failure: function(){
					console.log("Error!");
				}    			
			}
		}
	
		var url = 'http://vm33.netexlearning.cloud/mvc/rest/v1/customers/7/schools';
		Y.io(url, config);
	});
});
