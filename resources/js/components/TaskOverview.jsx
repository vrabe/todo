import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import Task from './Task';

export default class TaskOverview extends Component {
    render() {
        return (
            <div className="content-wrapper">
                { /* Content Header (Page header) */ }
                <div className="content-header">
                    <div className="container-fluid">
                        <div className="row mb-2">
                            <div className="col-sm-6">
                                <h1 className="m-0 text-dark">Starter Page</h1>
                            </div>
                            <div className="col-sm-6">
                                <ul className="nav nav-pills float-sm-right">
                                    <li className="nav-item"><a className="nav-link" href="#">Home</a></li>
                                    <li className="nav-item"><a className="nav-link active" href="#">Starter Page</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                { /* /.content-header */ }

                { /* Main content */ }
                <div className="content">
                    <div className="container-fluid">
                        <div className="add-task mb-3">
                            <div className="form-control add-task-input">
                                <label htmlFor="formGroupExampleInput" className="add-task-input-icon">
                                    <FontAwesomeIcon icon="plus" />
                                </label>
                                <input type="text" className="" id="formGroupExampleInput" placeholder="Example input" />
                                <a className="add-task-input-icon" data-toggle="collapse" href="#collapseExample">
                                    <FontAwesomeIcon icon="caret-down" />
                                </a>
                            </div>
                            <div className="collapse" id="collapseExample">
                                <div className="add-task-input-option">
                                    <FontAwesomeIcon icon={['far', 'calendar']} />
                                    <FontAwesomeIcon icon={['far', 'bell']} />
                                    <FontAwesomeIcon icon={['far', 'hourglass']} />
                                </div>
                            </div>
                        </div>
                        { /* TO DO List */ }
                        <ul className="task-list">
                            <Task />
                        </ul>
                    </div>
                </div>
                { /* /.content */ }
            </div>
        );
    }
}
