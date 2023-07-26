import express from "express";
import cors from "cors";
import http from "http";
import { Server } from "socket.io";
import mysql from "mysql2";
import { match } from "assert";

const app = express();
app.use(cors());
const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: ["http://localhost:3002", "http://127.0.0.1:3002"],
    },
});

const PORT = process.env.PORT || 8000;

// Konfigurasi koneksi database
const dbConfig = {
    host: "localhost",
    user: "root",
    password: "root",
    port: 3306,
    database: "kuis2",
};

const connection = mysql.createConnection(dbConfig);

let users = [];
let turn = "X";
let board = Array(9).fill("");
const availableRooms = new Set();

io.on("connection", (socket) => {
    console.log(io.sockets.adapter.rooms);
    socket.on("sendUser", (data) => {
        let user = {
            socketId: socket.id,
            symbol: users.length === 0 ? "X" : "O",
        };

        user = { ...user, ...data };
        users.push(user);

        console.log(availableRooms);

        if (users.length === 1) {
            // Check if there are available rooms
            const availableRoom = getAvailableRoom();

            if (availableRoom) {
                // Assign the socket to the available room
                socket.join(availableRoom);
                socket.emit("waiting");
            } else {
                // Create a new room and assign the socket to it
                const newRoom = createNewRoom();
                socket.join(newRoom);
                socket.emit("waiting");
            }
        } else if (users.length === 2) {
            // When there are two users, try to find and pair male and female users
            findAndPairUsers();
        } else {
            // Emit a message indicating that the room is full
            socket.emit("roomFull");
        }

        console.log(users);
        console.log(users.length);
    });

    socket.on("play", () => {
        if (users.length === 2) {
            // Emit the 'play' event after both players have joined
            io.emit("start", {
                users: users,
                turn: turn,
                board: board,
            });
        }
    });

    socket.on("move", (data) => {
        board = data.board;
        turn = data.turn;
        io.emit("move", {
            board: board,
            turn: turn,
            i: data.i,
        });

        turn = turn === "X" ? "O" : "X";
        io.emit("turn", turn);

        let winner = checkWinner(board);
        if (winner !== "") {
            users = [];
            // make board is empty
            board = Array(9).fill("");
            io.emit("win", winner);
        }

        // Check for draw
        if (!board.includes("") && winner === "") {
            users = [];
            board = Array(9).fill("");
            io.emit("draw");
        }
    });

    socket.on("disconnect", () => {
        users = users.filter((user) => user.socketId !== socket.id);
    });
});

function checkWinner(board) {
    const lines = [
        [0, 1, 2],
        [3, 4, 5],
        [6, 7, 8],
        [0, 3, 6],
        [1, 4, 7],
        [2, 5, 8],
        [0, 4, 8],
        [2, 4, 6],
    ];

    for (const line of lines) {
        const [a, b, c] = line;
        if (board[a] && board[a] === board[b] && board[a] === board[c]) {
            return board[a];
        }
    }
    return "";
}

function getAvailableRoom() {
    const rooms = io.sockets.adapter.rooms;
    console.log(rooms[0]);
    for (const room of availableRooms) {

        if (!rooms.get(room) || rooms.get(room).size < 2) {
            return room;
        }
    }
    return null;
}

function createNewRoom() {
    let newRoom = "";
    do {
        newRoom = Math.floor(Math.random() * 10000).toString();
    } while (availableRooms.has(newRoom));
    availableRooms.add(newRoom);
    return newRoom;
}

function findAndPairUsers() {
    const maleUser = users.find((u) => u.gender === "male");
    const femaleUser = users.find((u) => u.gender === "female");

    if (
        maleUser &&
        femaleUser &&
        maleUser.dating_code === femaleUser.dating_code &&
        maleUser.gender !== femaleUser.gender
    ) {
        // Clear users array to reset for the next round
        users = [];
        io.emit("clearBoard");

        // Update the users array with partner info
        maleUser.partner = {
            profile_picture: femaleUser.profile_picture,
            name: femaleUser.name,
            phone: femaleUser.phone,
            birthday: femaleUser.birthday,
        };
        femaleUser.partner = {
            profile_picture: maleUser.profile_picture,
            name: maleUser.name,
            phone: maleUser.phone,
            birthday: maleUser.birthday,
        };

        let matchID = "";
        matchID = Math.floor(Math.random() * 10000);

        connection.query(
            "INSERT INTO match_partners(user_id, partner_id, match_id) VALUES (?, ?, ?)",
            [maleUser.id, femaleUser.id, matchID],
            (err, result) => {
                if (err) {
                    console.log(err);
                } else {
                    console.log("berhasil");
                }
            }
        );

        io.to(maleUser.socketId).emit("foundPartner", maleUser);
        io.to(femaleUser.socketId).emit("foundPartner", femaleUser);
    } else {
        io.emit("start", {
            users: users,
            turn: turn,
            board: board,
        });
        console.log("masuk start");
    }
}

server.listen(PORT, () => {
    console.log("Listening on: http://localhost:" + PORT + "/");
});