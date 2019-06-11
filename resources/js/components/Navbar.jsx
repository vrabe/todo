import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'

export default class Navbar extends Component {
    render() {
        return (
            <nav className="main-header navbar navbar-expand bg-white navbar-light border-bottom">
                { /* Left navbar links */ }
                <ul className="navbar-nav">
                    <li className="nav-item">
                        <a className="nav-link" data-widget="pushmenu" href="#">
                            <FontAwesomeIcon icon="bars" className="fa" />
                        </a>
                    </li>
                    <li className="nav-item d-none d-sm-inline-block">
                        <a href="index3.html" className="nav-link">Home</a>
                    </li>
                    <li className="nav-item d-none d-sm-inline-block">
                        <a href="#" className="nav-link">Contact</a>
                    </li>
                </ul>

                { /* SEARCH FORM */ }
                <form className="form-inline ml-3">
                    <div className="input-group input-group-sm">
                        <input className="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" />
                        <div className="input-group-append">
                            <button className="btn btn-navbar" type="submit">
                                <FontAwesomeIcon icon="search" className="fa" />
                            </button>
                        </div>
                    </div>
                </form>

                { /* Right navbar links */ }
                <ul className="navbar-nav ml-auto">
                    { /* Messages Dropdown Menu */ }
                    <li className="nav-item dropdown">
                        <a className="nav-link" data-toggle="dropdown" href="#">
                            <FontAwesomeIcon icon={['far', 'comments']} className="fa" />
                            <span className="badge badge-danger navbar-badge">3</span>
                        </a>
                        <div className="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <a href="#" className="dropdown-item">
                                { /* Message Start */ }
                                <div className="media">
                                    <img src="dist/img/user1-128x128.jpg" alt="User Avatar" className="img-size-50 mr-3 img-circle" />
                                    <div className="media-body">
                                        <h3 className="dropdown-item-title">
                                            Brad Diesel
                                            <span className="float-right text-sm text-danger">
                                                <FontAwesomeIcon icon="star" className="fa" />
                                            </span>
                                        </h3>
                                        <p className="text-sm">Call me whenever you can...</p>
                                        <p className="text-sm text-muted">
                                            <FontAwesomeIcon icon={['far', 'clock']} className="fa" />
                                            4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                { /* Message End */ }
                            </a>
                            <div className="dropdown-divider"></div>
                            <a href="#" className="dropdown-item">
                                { /* Message Start */ }
                                <div className="media">
                                    <img src="dist/img/user8-128x128.jpg" alt="User Avatar" className="img-size-50 img-circle mr-3" />
                                    <div className="media-body">
                                        <h3 className="dropdown-item-title">
                                            John Pierce
                                            <span className="float-right text-sm text-muted">
                                                <FontAwesomeIcon icon="star" className="fa" />
                                            </span>
                                        </h3>
                                        <p className="text-sm">I got your message bro</p>
                                        <p className="text-sm text-muted">
                                            <FontAwesomeIcon icon={['far', 'clock']} className="fa" />
                                            4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                { /* Message End */ }
                            </a>
                            <div className="dropdown-divider"></div>
                            <a href="#" className="dropdown-item">
                                { /* Message Start */ }
                                <div className="media">
                                    <img src="dist/img/user3-128x128.jpg" alt="User Avatar" className="img-size-50 img-circle mr-3" />
                                    <div className="media-body">
                                        <h3 className="dropdown-item-title">
                                            Nora Silvester
                                            <span className="float-right text-sm text-warning">
                                                <FontAwesomeIcon icon="star" className="fa" />
                                            </span>
                                        </h3>
                                        <p className="text-sm">The subject goes here</p>
                                        <p className="text-sm text-muted">
                                            <FontAwesomeIcon icon={['far', 'clock']} className="fa" />
                                            4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                { /* Message End */ }
                            </a>
                            <div className="dropdown-divider"></div>
                            <a href="#" className="dropdown-item dropdown-footer">See All Messages</a>
                        </div>
                    </li>
                    { /* Notifications Dropdown Menu */ }
                    <li className="nav-item dropdown">
                        <a className="nav-link" data-toggle="dropdown" href="#">
                            <FontAwesomeIcon icon={['far', 'bell']} className="fa" />
                            <span className="badge badge-warning navbar-badge">15</span>
                        </a>
                        <div className="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <span className="dropdown-header">15 Notifications</span>
                            <div className="dropdown-divider"></div>
                            <a href="#" className="dropdown-item">
                                <FontAwesomeIcon icon="envelope" className="fa mr-2" /> 4 new messages
                                <span className="float-right text-muted text-sm">3 mins</span>
                            </a>
                            <div className="dropdown-divider"></div>
                            <a href="#" className="dropdown-item">
                                <FontAwesomeIcon icon="users" className="fa mr-2" /> 8 friend requests
                                <span className="float-right text-muted text-sm">12 hours</span>
                            </a>
                            <div className="dropdown-divider"></div>
                            <a href="#" className="dropdown-item">
                                <FontAwesomeIcon icon="file" className="fa mr-2" /> 3 new reports
                                <span className="float-right text-muted text-sm">2 days</span>
                            </a>
                            <div className="dropdown-divider"></div>
                            <a href="#" className="dropdown-item dropdown-footer">See All Notifications</a>
                        </div>
                    </li>
                    <li className="nav-item">
                        <a className="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><FontAwesomeIcon icon="th-large" className="fa" /></a>
                    </li>
                </ul>
            </nav>
        );
    }
}
