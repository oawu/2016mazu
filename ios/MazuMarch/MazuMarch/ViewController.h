//
//  ViewController.h
//  MazuMarch
//
//  Created by OA Wu on 2016/3/23.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <CoreLocation/CoreLocation.h>
#import "Path.h"
#import "AFHTTPRequestOperationManager.h"

@interface ViewController : UIViewController <CLLocationManagerDelegate>

@property UITextView *logTextView;
@property UISwitch *switchButton;
@property UILabel *switchLabel;
@property CLLocationManager *locationManager;

@end

