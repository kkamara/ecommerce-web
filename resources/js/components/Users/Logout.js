import React, { Component, Fragment } from "react";
import { connect } from "react-redux";
import { withRouter, Redirect } from "react-router-dom";

import { userActions } from "../../redux/actions/index";

class Logout extends Component {
    componentDidMount() {
        const { fetched, isLoaded, user } = this.props.current_user;
        const { history } = this.props;

        if (typeof user !== "undefined") {
            history.push("/404");
        } else {
            this.props.logoutUser();

            setTimeout(() => history.push("/"), 1000);
        }
    }

    render() {
        return <div />;
    }
}

const mapStateToProps = state => ({
    user: state.user.user
});
const mapDispatchToProps = dispatch => ({
    logoutUser: () => dispatch(userActions.logoutUser())
});
export default connect(
    mapStateToProps,
    mapDispatchToProps
)(withRouter(Logout));
