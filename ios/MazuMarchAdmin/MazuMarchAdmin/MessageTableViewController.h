//
//  MessageTableViewController.h
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/28.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "Message.h"
#import "MessageTableViewCell.h"
#import "AFHTTPRequestOperationManager.h"
#import "Header.h"

@interface MessageTableViewController : UITableViewController

@property int maxId;
@property NSTimer *timer;
@property UIRefreshControl *refreshControl;
@property bool isLoading;
@property NSMutableArray<Message *> *messages;
@end
