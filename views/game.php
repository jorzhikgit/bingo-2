<? include '_header.php' ?>

<div class="container game-container" ng-controller="GameController">

	<form ng-if="!started" name="form">
		<div class="row">
			<div class="col-lg-3 row">
				<label> Cards number </label>
				<input type="number" class="form-control" ng-model="data.cardsNumber" max="8"/>
				<small class="help-block"> You can get from 1 to 8 cards</small>
			</div>
		</div>

		<div class="row">
			<a class="btn btn-primary" ng-click="start();" href="#">
				Start game
			</a>
		</div>
	</form>

	<div class="row" ng-if="started && !won" style="margin-top: 30px;">
		<a class="btn btn-success" ng-click="nextNumber()" ng-disabled="auto.started"> Следующее число </a>
		<a class="btn btn-warning" ng-click="toggleAuto()">
			{{ auto.started ? 'Stop auto' : 'Start auto' }}
		</a>

		<a class="btn btn-danger" ng-click="playAll()" ng-disabled="auto.started"> Разыграть </a>
		<div ng-if="current">
			Последнее число {{ current }}.
			<span class="label-success" ng-if="numbers[current]"> Есть такое число </span>
			<span class="label-danger" ng-if="!numbers[current]"> У вас нет такого числа </span>
		</div>
	</div>

	<div class="row" ng-if="won" style="margin-top: 30px;">
		Вы выиграли Бинго. Ваше счастливое число {{ winningNumber }}
	</div>

	<hr/>

	<div ng-if="started">
		<table ng-repeat="(cardNumber, card) in cards" class="card" ng-class="{ 'full-card': card.won }">
			<tr ng-repeat="row in card.items" class="card-row" ng-class="{ 'full-row': row.won }">
				<td ng-repeat="i in getCols()" class="card-cell" ng-class="{ 'has-been': row.items[i].hasBeen, current: current && row.items[i].number == current }">
					&nbsp; {{row.items[i].number}} &nbsp;
				</td>
			</tr>
		</table>
	</div>

</div>

<script src="/javascripts/game-module.js"></script>
<script>
	$(function() {
		angular.bootstrap(document, ['bingo']);
	});
</script>

<? include '_footer.php' ?>