import React, { Component } from 'react';
import {AsyncStorage, AppRegistry} from 'react-native';
import { Icon, Text, TabHeading, ScrollableTab, Container, Content, Tab, Tabs } from 'native-base';
import {Actions, Scene, Router} from 'react-native-router-flux';

import Tasks from './tasks';
import AddNewTask from './addNewTask';
import Rewards from './rewards';

export default class Habit2Reward extends Component {
  tasks = []
  components = []
  
  addToSync(c) {
    this.components.push(c)
    this.sync()
  }
  
  sync() {
    this.components.forEach(function(c) {
      c(this.tasks)
    }.bind(this));
  }
  
  async save(tasks) {
    try {
      await AsyncStorage.setItem('@MyStore:tasks', JSON.stringify(tasks))
      this.tasks = tasks
      this.sync()
    } catch (error) { }
  }
  
  async componentWillMount() {
    try {
      let tasks = await AsyncStorage.getItem('@MyStore:tasks');
      if (tasks !== null) {
        this.tasks = JSON.parse(tasks)
        this.sync()
      }
    } catch (error) { }
  }

  render() {
    return (
      <Container>
        <Tabs>
          <Tab heading={ <TabHeading><Icon name="ios-checkbox-outline" /><Text>Tasks</Text></TabHeading>}>
            <Router hideNavBar={true}>
              <Scene key="root">
                <Scene key="tasks" component={Tasks} title="tasks" initial={true} save={(t) => this.save(t)} addToSync={(c) => this.addToSync(c)} />
                <Scene key="addNewTask" component={AddNewTask} title="addNewTask"/>
              </Scene>
            </Router>
          </Tab>
          <Tab heading={ <TabHeading><Icon name="ios-star" /><Text>Rewards</Text></TabHeading>}>
            <Rewards save={(t) => this.save(t)} addToSync={(c) => this.addToSync(c)} />
          </Tab>
        </Tabs>
      </Container>
    );
  }
}

AppRegistry.registerComponent('Habit2Reward', () => Habit2Reward);
