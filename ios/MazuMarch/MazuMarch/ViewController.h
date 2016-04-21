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
#import "LockViewController.h"
#import "March.h"

@interface ViewController : UIViewController <CLLocationManagerDelegate, UIPickerViewDelegate>

@property UITextView *locationLogTextView;
@property UITextView *uploadLogTextView;
@property UISwitch *switchButton;
@property UILabel *switchLabel;
@property UISegmentedControl *segmentedControl;
@property UIStepper *stepper;
@property UIPickerView *picker;
@property UILabel *stepperLabel;

@property NSMutableArray<March *> *marchs;

@property CLLocationManager *locationManager;
@property NSTimer *timer;
@property BOOL isUpload;
@property int marchId;
@property int distance;
@property UIDevice *myDevice;

@property UIView *pswView;
@property NSLayoutConstraint *left1, *left2;

@property UIBackgroundTaskIdentifier *backgroundTaskIdentifier;

@end

