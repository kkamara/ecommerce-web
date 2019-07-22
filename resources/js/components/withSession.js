import React, { Component } from "react";

import { getCurrentUser } from "../requests/user";
import { connect } from "react-redux";

export default function(ComposedComponent) {
    class withSession extends Component {
        componentDidMount() {
            this.loadCurrentUser();
        }

        loadCurrentUser() {
            this.props.dispatch({
                type: "FETCH_CURRENT_USER",
                payload: getCurrentUser()
            });
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

    return connect(mapStateToProps)(withSession);
}
