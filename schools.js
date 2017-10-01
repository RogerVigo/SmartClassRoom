YUI().use('node', 'io', 'dump', 'json-parse', 'io-xdr', function(Y){
	Y.all('tr button').on('click', function(e){
		var randomCode = Math.floor(Math.random());

		var customerID = Y.one("table.admintable").getAttribute("data-customerID");
		var token = Y.one("table.admintable").getAttribute("data-token");
		//console.log(this.ancestor().siblings("td.name")._nodes[0].innerHTML);
		/*var data = '{"school":{"id":"2","ltiConsumerPassword":"'+this.ancestor().siblings("td.ltiPass")._nodes[0].innerHTML+'","name":"'+this.ancestor().siblings("td.name")._nodes[0].innerHTML+'","schoolCode":"'+ this.ancestor().siblings("td.schoolCode")._nodes[0].innerHTML+'"}}';*/
		//var data = '{"school":{"id":"2","ltiConsumerPassword":"1234","name":"'+this.ancestor().siblings("td.name")._nodes[0].innerHTML+'","schoolCode":"'+ randomCode +'"}}';
		/*console.log(Y.JSON.parse(data));*/
		var config = {
			method: 'POST',
			/*data: Y.JSON.parse(data),*/
			headers: {
				'Authorization': 'Bearer '+token,
				'Content-Type': 'application/json',
			},
			on: {
				success: function(id, response, args){
					console.log("Yey!");

				},
				failure: function(){
					console.log("Error!");
					//console.log(e.currentTarget._node);
					var imgID = 'imgState' + document.getElementById(e.currentTarget._node.id).getAttribute('data-value');
					document.getElementById(imgID).setAttribute('src',"pix/i/warning.png");			
				}    			
			}
		}
	
		var url = 'http://vm33.netexlearning.cloud/mvc/rest/v1/customers/'+customerID+'/schools';
		Y.io(url, config);
	});
});
