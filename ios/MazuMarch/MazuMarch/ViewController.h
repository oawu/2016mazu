//
//  ViewController.h
//  MazuMarch
//
//  Created by OA Wu on 2016/3/23.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <CoreLocation/CoreLocation.h>
#import "Header.h"
#import "Path.h"
#import "AFHTTPRequestOperationManager.h"

@interface ViewController : UIViewController <CLLocationManagerDelegate>

@property UITextView *locationLogTextView;
@property UITextView *uploadLogTextView;
@property UISwitch *switchButton;
@property UILabel *switchLabel;
@property UISegmentedControl *segmentedControl;
@property UIStepper *stepper;
@property UILabel *stepperLabel;

@property CLLocationManager *locationManager;
@property NSTimer *timer;
@property BOOL isUpload;
@property int marchId;
@property int distance;

@end

