import React, { Component } from "react";

import { userActions } from "../redux/actions/index";
import { connect } from "react-redux";

export default function(ComposedComponent) {
    class withSession extends Component {
        componentDidMount() {
            this.loadCurrentUser();
        }

        loadCurrentUser() {
            this.props.getCurrentUser();
        }

        render() {
            const { current_user } = this.props;

            const userAuthenticated =
                current_user.isLoaded && current_user.fetched;

            return (
                <ComposedComponent
                    {...this.props}
                    current_user={current_user}
                    userAuthenticated={userAuthenticated}
                />
            );
        }
    }

    const mapStateToProps = state => ({
        current_user: state.user.user
    });
    const mapDispatchToProps = dispatch => ({
        getCurrentUser: () => dispatch(userActions.getCurrentUser())
    });

    return connect(
        mapStateToProps,
        mapDispatchToProps
    )(withSession);
}
