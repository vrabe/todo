import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'

export default class Task extends Component {
    render() {
        return (
            <li className="primary">
                <div className="abc-todo-checkbox">
                    <input id="checkbox1" type="checkbox" />
                    <label></label>
                </div>
                <div className="task-content">
                    <span className="task-number">000000</span><br />
                    <span className="text">Check your messages and notifications</span>
                    <span>&nbsp;</span>
                    <small className="badge badge-primary">
                        <FontAwesomeIcon icon={['far', 'clock']} />
                        &nbsp;1 week
                    </small>
                </div>
            </li>
        );
    }
}
