angular.module('bingo', [])
	.service('bingoGame', [function() {

	}])
	.controller('GameController', ['$scope', '$http', '$interval', function ($scope, $http, $interval) {

		$scope.data = {
			cardsNumber: 1
		};
		$scope.started = false;
		$scope.cards = {};
		$scope.numbers = {};
		$scope.turn = 0;
		$scope.current = null;
		$scope.awards = {
			rows: 0,
			bingo: false
		};

		$scope.auto = {
			started: false
		};

		$scope.start = function() {
			$http.get('/game/create/' + $scope.data.cardsNumber)
				.success(function(data) {
					$scope.gameId = data.id;
					$scope.setCards(data.cards);
				});
		};

		$scope.setCards = function(cards) {
			$scope.started = true;

			var numbers = {};

			angular.forEach(cards, function(card, cardNumber) {
				cards[cardNumber] = {
					items: card,
					length: 15,
					won: false
				};
				angular.forEach(card, function(row, rowNumber) {
					card[rowNumber] = {
						items: row,
						length: 5,
						won: false
					};
					angular.forEach(row, function(number, cell) {
						numbers[number] || (numbers[number] = []);
						numbers[number].push(row[cell] = {
							number: number,
							cell: cell,
							hasBeen: false,
							row: card[rowNumber],
							card: cards[cardNumber]
						});
					});
				});
			});

			$scope.cards = cards;
			$scope.numbers = numbers;
		}

		$scope.newTurn = function(number) {
			var cells = $scope.numbers[number];
			$scope.current = number;

			angular.forEach(cells, function(cell) {
				if(cell.hasBeen) {
					return;
				}
				cell.hasBeen = true;

				if(!--cell.row.length) {
					cell.row.won = true;
					$scope.awards.rows++;
				}

				if(!--cell.card.length) {
					cell.card.won = true;
					$scope.awards.bingo = true;
					$scope.win(number);
				}
			});
		}

		$scope.win = function(number) {
			$scope.won = true;
			$scope.winningNumber = number;

			stopAuto();
		}

		$scope.nextNumber = function() {
			$http.get('/game/' + $scope.gameId + '/turn/' + $scope.turn)
				.success(function(data) {
					$scope.newTurn(data.number);
					$scope.turn++;
				})
		};

		$scope.playAll = function() {
			$http.get('/game/' + $scope.gameId + '/playAll/' + $scope.turn)
				.success(function(data) {
					angular.forEach(data.numbers, function(number) {
						$scope.newTurn(number);
					});
				})
		};

		$scope.toggleAuto = function() {
			$scope.auto.started ? stopAuto() : startAuto();
		};

		function stopAuto() {
			$scope.auto.started = false;
			$interval.cancel($scope.auto.promise);
		}

		function startAuto() {
			$scope.auto.started = true;
			$scope.auto.promise = $interval(function() {
				$scope.nextNumber();
			}, 1000);
		}

		(function() {
			var cols = [1, 2, 3, 4, 5, 6, 7, 8, 9];

			$scope.getCols = function() {
				return cols;
			};
		})();
	}]);