//
//  SettingViewController.h
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/4/22.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "Header.h"
#import "AFHTTPRequestOperationManager.h"

@interface SettingViewController : UIViewController

@property UIScrollView *scrollView;

@property UILabel *pathTitleLabel;
@property UILabel *versionTitleLabel;
@property UILabel *versionLabel;
@property UILabel *crontabTitleLabel;

@property UISegmentedControl *pathSegmentedControl;
@property UIStepper *versionSteper;
@property UISwitch *crontabSwitch;
@end
