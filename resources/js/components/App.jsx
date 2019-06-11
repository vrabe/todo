import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import Navbar from './Navbar';
import Sidebar from './Sidebar';
import TaskOverview from './TaskOverview';

export default class App extends Component {
    render() {
        return (
            <div>
                <Navbar />
                <Sidebar />
                <TaskOverview />
            </div>
        );
    }
}

if (document.getElementById('app')) {
    ReactDOM.render(<App />, document.getElementById('app'));
}
