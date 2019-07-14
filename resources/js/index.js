import { BrowserRouter, Switch, Redirect, Route } from "react-router-dom";
import React, { Fragment } from "react";
import ReactDOM from "react-dom";

import Navbar from "./components/Navbar.js";
import App from "./components/App.js";

const Root = () => (
    <BrowserRouter>
        <Fragment>
            <Navbar />
            <Switch>
                <Route path="/" exact component={App} />
                <Redirect to="/" />
            </Switch>
        </Fragment>
    </BrowserRouter>
);

ReactDOM.render(<Root />, document.getElementById("root"));

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: https://bit.ly/CRA-PWA
