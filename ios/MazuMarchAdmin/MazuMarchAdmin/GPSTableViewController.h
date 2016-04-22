//
//  GPSTableViewController.h
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/4/22.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "Header.h"
#import "AFHTTPRequestOperationManager.h"
#import "March.h"
#import "GPSMarchViewController.h"

@interface GPSTableViewController : UITableViewController

@property NSMutableArray<March *> *marches;
@end
