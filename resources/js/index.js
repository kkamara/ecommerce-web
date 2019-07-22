import { BrowserRouter, Switch, Redirect, Route } from "react-router-dom";
import React, { Fragment } from "react";
import ReactDOM from "react-dom";

import Navbar from "./components/Navbar.js";
import App from "./components/App.js";
import ProductPage from "./components/Products/ProductPage";

import logger from "redux-logger";
import { applyMiddleware, createStore, combineReducers } from "redux";
import thunk from "redux-thunk";
import promise from "redux-promise-middleware";
import { Provider } from "react-redux";
import reducers from "./reducers/index.js";

import requireSession from "./components/withSession";

const middleware = applyMiddleware(promise, thunk, logger);
const store = createStore(reducers, middleware);

const Root = () => (
    <BrowserRouter>
        <Fragment>
            <Navbar />
            <Switch>
                <Route path="/" exact component={requireSession(App)} />
                <Route
                    path="/products/:id"
                    exact
                    component={requireSession(ProductPage)}
                />
                <Redirect to="/" />
            </Switch>
        </Fragment>
    </BrowserRouter>
);

// const RootWithSession = withSession(Root);

ReactDOM.render(
    <Provider store={store}>
        <Root />
    </Provider>,
    document.getElementById("root")
);

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: https://bit.ly/CRA-PWA
