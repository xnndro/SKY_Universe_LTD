@extends('layouts.masterUser')

@section('content')
<div class="flex items-center justify-center bg-gray-900 w-screen h-screen">
    <div class="text-center">
        <h1 class="text-3xl text-white font-bold">Tic Tac Toe</h1>
        <h1 class="text-sm text-white" id="message">Waiting...</h1>
        <div class="mt-2 gap-2 bg-gray-800 w-full h-full grid grid-rows-3 grid-cols-3" id="board"></div>
        <div class="row">
            <div class="col-lg-4"></div>
        <div class="col-lg-4">
            <div class="justify-content-center">
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="card card-profile hidden w-80 mt-4" id="partnerFound">
                        <!-- Add 'bg-white' class for white background -->
                        <img src="../assets/img/bg-profile.jpg" alt="Image placeholder" class="card-img-top" />
                        <div class="row justify-content-center">
                            <div class="col-4 col-lg-4 order-lg-2">
                                <div class="mt-n4 mt-lg-n6 mb-4 mb-lg-0">
                                    <a href="javascript:;">
                                        <img src=""
                                            class="rounded-circle object-fit-cover h-24 w-24 mx-auto border border-2 border-white rounded-full"
                                            alt="PartnerProfile" id="partnerProfilePicture" />
                                    </a>
                                </div>
                            </div>
                        </div>
    
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="text-center mt-4">
                                    <p class="text-2xl font-bold" id="partnerName" name="partnerName"></p>
                                    <p class="text-gray-700 text-base" id="partnerInfo"></p>
    
                                    <div class="px-6 pb-2 pt-3">
                                        <a class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded"
                                            href="{{route('places')}}">
                                            Find Wedding Location
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4"></div>
        </div>
    </div>
</div>




<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.socket.io/4.6.0/socket.io.min.js"
    integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous">
</script>
<script>
    let board = Array(9).fill('');
        let user = @json(Auth::user());
        let turn = '';

        var socket = io('http://localhost:8000', [
            {
                transports: ['websocket'],
            }
        ]);

        socket.on('connect', () => {
            event.preventDefault();
            board = [];
            document.getElementById('message').innerHTML = 'Connected';
            socket.emit('sendUser', user);
            clearBoard();
        });

        socket.on('waiting', () => {
            document.getElementById('message').innerHTML = 'Waiting for another player...';
            clearBoard();
            // hide board
            document.getElementById('board').classList.add('hidden');
        });

        socket.on('start', (data) => {
            users = user = data.users.find(u => u.socketId === socket.id);
            turn = data.turn;
            board = data.board;
            renderBoard();

            document.getElementById('message').innerHTML = `You are ${user ? user.symbol : 'X'} and it is ${turn}'s turn`;
        });

        socket.on('play', () => {
            document.getElementById('message').innerHTML = `You are ${user ? user.symbol : 'X'} and it is ${turn}'s turn`;
        });

        socket.on('turn', (data) => {
            turn = data;
            document.getElementById('message').innerHTML = `You are ${user ? user.symbol : 'X'} and it is ${turn}'s turn`;
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
                board = Array(9).fill('');
                clearBoard();
                renderBoard();
                document.getElementById('message').innerHTML = 'Waiting for another player...';
            }, 5000);
            window.location.reload();
        });

        socket.on('draw', () => {
            document.getElementById('message').innerHTML = 'It\'s a draw';

            setTimeout(() => {
                board = Array(9).fill('');
                clearBoard();
                renderBoard();
                document.getElementById('message').innerHTML = 'Waiting for another player...';
            }, 5000);
            
            window.location.reload();
        });

        socket.on('foundPartner', (partner) => {
            document.getElementById('message').innerHTML = 'Partner found!';

            // Tampilkan elemen pasangan
            document.getElementById('partnerFound').classList.remove('hidden');

            console.log(partner.partner);
            // Tampilkan informasi pasangan yang diterima dari server
            document.getElementById('partnerProfilePicture').src = 'public/uploads/profile/' + partner.partner.profile_picture;
            document.getElementById('partnerName').innerHTML = partner.partner.name;
            document.getElementById('partnerInfo').innerHTML = 'Hi, This is my personal info for you, my birthday date is ' + partner.partner.birthday + ' and this is my phone number, call me! ' + partner.partner.phone;
        });

        socket.on('clearBoard', () => {
            board = Array(9).fill('');
            renderBoard();
            document.getElementById('board').classList.add('hidden');
        });

        socket.on('disconnect', () => {
            document.getElementById('message').innerHTML = 'Disconnected';
            setTimeout(() => {
                window.location.reload();
            }, 5000);
            clearBoard();
            window.location.reload();
        });

        // window.onload = function () {
        //     renderBoard();
        // }
        socket.on('roomFull', () => {
            document.getElementById('message').innerHTML = 'The room is full. Please try again later.';
            clearBoard();
            // hide board
            document.getElementById('board').classList.add('hidden');
        });
        
        function renderBoard() {
            let boardDiv = document.getElementById('board');
            let boardhtml = '';
            for (let i = 0; i < board.length; i++) {
                boardhtml += `<div onclick="handleClick(${i})" class="cursor-pointer w-32 h-32 text-5xl text-white grid place-content-center border-2 ${board[i] === 'O' ? 'bg-red-500' : board[i] === 'X' ? 'bg-green-500' : ''}">${board[i]}</div>`
            }
            boardDiv.innerHTML = boardhtml;
        }

        function handleClick(i) {
            if (board[i] === '') {
                if (turn === user.symbol) {
                    board[i] = user.symbol;
                    socket.emit('move', {
                        board: board,
                        turn: turn,
                        i: i,
                    });
                }
            }
        }
</script>

@endsection