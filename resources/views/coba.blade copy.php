<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

  <div class="bg-gray-900 w-screen h-screen text-center grid place-content-center">
    <h1 class="text-3xl text-white font-bold">Tic Tac Toe</h1>
    <h1 class="text-sm text-white" id="message">Message</h1>
    <div class="mt-2 gap-2 bg-gray-800 w-full h-full grid grid-rows-3 grid-cols-3" id="board">
        
    </div>
  </div>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.socket.io/4.6.0/socket.io.min.js" integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous"></script>
  <script>
    let board = [];
let user = null;
let users =[];
let turn = '';

var socket = io('http://localhost:8000',[
    {
        transports: ['websocket'],
    }
]);
socket.on('connect', () => {
    board = [];
    user = null;
    document.getElementById('message').innerHTML = 'Connected';
});

socket.on('full', () => {
    document.getElementById('message').innerHTML = 'Room is full';
});

socket.on('setUser', (data) => {
    user = data;
    console.log(user);
});

socket.on('start', (data) => {
    users = data.users;
    turn = data.turn;
    board = data.board;
    renderBoard();

    document.getElementById('message').innerHTML = `You are ${user.symbol} and it is ${turn}'s turn`;
});

socket.on('turn', (data) => {
    turn = data;
    document.getElementById('message').innerHTML = `You are ${user.symbol} and it is ${turn}'s turn`;
});

socket.on('move', (data) => {
    board = data.board;
    turn = data.turn;
    renderBoard();
});

socket.on('win', (data) => {
    document.getElementById('message').innerHTML = `${data} wins`;
    // auto reload after 5 seconds
    setTimeout(() => {
        window.location.reload();
    });
});

socket.on('disconnect', () => {
    document.getElementById('message').innerHTML = 'Disconnected';
    setTimeout(() => {
        window.location.reload();
    });
});

window.onload = function() {
    renderBoard();
}

function renderBoard()
{
    let boardDiv = document.getElementById('board');
    let boardhtml = '';
    for (let i = 0; i < board.length ; i++) {
        boardhtml += `<div onclick="handleClick(${i})" class="cursor-pointer w-32 h-32 text-5xl text-white grid place-content-center border-2 ${board[i] === 'O'? 'bg-red-500': board[i]==='X'?'bg-green-500':''}">${board[i]}</div>`
    }
    boardDiv.innerHTML = boardhtml;
}
function handleClick(i)
{
    if(board[i] === '')
    {
        if(turn === user.symbol)
        {
            board[i] = user.symbol;
            socket.emit('move', {
                board : board,
                turn : turn,
                i: i,
            });
        }
    }
} 
  </script>
</body>
</html>