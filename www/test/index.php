<!doctype html>
<html ng-app="test">
<head>
	<script src="http://img.zhangchu.cc/js/angular-1.4.5/angular.min.js"></script>
	<script>
		angular.module('test', []).controller('PhoneListCtrl', function($scope) {
			$scope.phones = [
				{'name': 'Nexus S',
					'snippet': 'Fast just got faster with Nexus S.',
					'age': 1},
				{'name': 'Motorola XOOM™ with Wi-Fi',
					'snippet': 'The Next, Next Generation tablet.',
					'age': 2},
				{'name': 'MOTOROLA XOOM™',
					'snippet': 'The Next, Next Generation tablet.',
					'age': 3}
			];

			$scope.orderProp = 'age';
			$scope.hello = "Hello, World!";
		});
	</script>
</head>
<body ng-controller="PhoneListCtrl">
Your name: <input type="text" ng-model="yourname" placeholder="World">
<hr>
Hello {{yourname || 'World'}}!

<p>1 + 2 = {{ 1 + 2 }}</p>

<p>Total number of phones: {{phones.length}}</p>

<p>{{hello}}</p>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-2">
			<!--Sidebar content-->

			Search: <input ng-model="query">
			Sort by:
			<select ng-model="orderProp">
				<option value="name">Alphabetical</option>
				<option value="age">Newest</option>
			</select>

		</div>
		<div class="col-md-10">
			<!--Body content-->

			<ul class="phones">
				<li ng-repeat="phone in phones | filter:query | orderBy:orderProp">
					{{phone.name}} {{phone.age}}
					<p>{{phone.snippet}}</p>
				</li>
			</ul>

		</div>
	</div>
</div>

</body>
</html>